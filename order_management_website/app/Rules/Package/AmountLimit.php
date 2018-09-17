<?php

namespace App\Rules\Package;

use App\Models\CaseModel;
use App\Models\PackageHasCases;
use Illuminate\Contracts\Validation\Rule;
use Request;

class AmountLimit implements Rule
{
    private $request;

    private $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request, $type)
    {
        $this->request = $request;
        $this->type = $type === 'create' ? 'create' : 'edit';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $cases
     * @return bool
     */
    public function passes($attribute, $cases)
    {
        if (empty($cases)) {
            return true;
        }

        $calculatedCaseAmount = [];
        foreach ($cases as $case) {
            if (!array_has($calculatedCaseAmount, $case['case_id'])) {
                $calculatedCaseAmount[$case['case_id']] = 0;
            }

            $calculatedCaseAmount[$case['case_id']] += $case['amount'];
        }

        foreach ($calculatedCaseAmount as $caseId => $totalInputAmount){
            $totalShippedAmountForCaseInDB = PackageHasCases::where('case_id', '=', $caseId)->sum('amount');
            $case = CaseModel::find($caseId);

            if ($this->type === 'create') {
                if ($case) {
                    if (($totalInputAmount + $totalShippedAmountForCaseInDB) > $case->amount) {
                        return false;
                    }
                }
            } else {
                $packageId = $this->request->id;
                $totalShippedAmountForPackageInDB = PackageHasCases::where(
                    'case_id', '=', $caseId
                )->where('package_id', '=', $packageId)
                    ->sum('amount');

                if ($case) {
                    if (($totalInputAmount + $totalShippedAmountForCaseInDB - $totalShippedAmountForPackageInDB) > $case->amount) {
                        return false;
                    }
                }
            }

        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.package_case_amount_limit');
    }
}
