<?php

namespace App\Rules\Package;

use App\Models\CaseModel;
use App\Models\PackageHasCases;
use Illuminate\Contracts\Validation\Rule;
use Request;

class AmountLimit implements Rule
{
    private $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
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

        foreach ($calculatedCaseAmount as $caseId => $totalAmount){
            $totalAmountInDB = PackageHasCases::where('case_id', '=', $caseId)->sum('amount');
            $case = CaseModel::find($caseId);
            if ($case) {
                if (($totalAmount + $totalAmountInDB) > $case->amount) {
                    return false;
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
