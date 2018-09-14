<?php

namespace App\Services\Features;


use App\Models\CaseModel;
use App\Models\Order;
use setasign\Fpdi\Tcpdf\Fpdi;

class OrderPDFGenerator
{
    protected $order;

    protected $fpdi;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->fpdi = new FPDI();

        $this->config();
    }

    public function create()
    {
        $this->setBasic();
        $this->setPackage();
        $this->setCase();
        $this->setRemark();
        $this->setCaseContent();

        $this->fpdi->Output('test.pdf', 'D');
    }

    private function config()
    {
        $this->fpdi->setPrintFooter(false);
        $this->fpdi->setPrintHeader(false);
        $this->fpdi->setSourceFile(storage_path('app/pdf/order_template.pdf'));
        $this->fpdi->SetFont('microsoftjhenghei', '', 14, storage_path('app/pdf/fonts/microsoftjhenghei.php'));

        $tplIdx = $this->fpdi->importPage(1);
        $this->fpdi->AddPage();
        $this->fpdi->useTemplate($tplIdx);
    }

    private function setBasic()
    {
        $this->fpdi->SetFontSize(14);

        $this->fpdi->Text(33, 28, $this->order->name ?: "");
        $this->fpdi->Text(120, 28, $this->order->phone ?: "");
        $this->fpdi->Text(33, 40, $this->order->married_date ? $this->order->married_date->format('Y-m-d') : "");
        $this->fpdi->Text(120, 40, $this->order->email ?: "");
    }

    private function setPackage()
    {
        $packageData = [];
        foreach ($this->order->packages as $package) {
            foreach ($package->cases as $content) {
                $arrivedDate = $package->arrived_at ? $package->arrived_at->format('Y-m-d') : "";
                $typeName = "";
                if ($content->case && $content->case->caseType) {
                    $typeName = $content->case->caseType->name;
                }
                $remark = preg_replace("/\r|\n/", " ", $package->remark);

                array_push($packageData, "$arrivedDate $typeName $content->amount");
                array_push($packageData, "$package->address $package->name $package->phone");
                array_push($packageData, "$remark");
            }
        }

        if (count($packageData) > 2) {
            $this->fpdi->SetFontSize(9);
        } else {
            $this->fpdi->SetFontSize(11);
        }

        foreach ($packageData as $key => $datum) {
            if ($datum) {
                $this->fpdi->Text(33, 50 + (4 * $key), $datum);
            }
        }
    }

    private function setCase()
    {
        $caseData = [
            "type" => [],
            "amount" => [],
            "price" => []
        ];

        $cases = CaseModel::select([
            'cases.id', 'case_type_id', 'case_types.name AS case_type_name', 'amount', 'price'
        ])->leftJoin('case_types', 'case_types.id', '=', 'cases.case_type_id')
            ->where('cases.order_id', $this->order->id)
            ->get();

        foreach ($cases as $case) {
            info($case);
            array_push($caseData['type'], $case->case_type_name ?: "");
            array_push($caseData['amount'], $case->amount);
            array_push($caseData['price'], $case->price);
        }

        $type = implode($caseData['type'], "/");
        $amount = implode($caseData['amount'], "/");
        $price = implode($caseData['price'], "/");

        if (strlen($type) > 16) {
            $this->fpdi->SetFontSize(9);
        } else {
            $this->fpdi->SetFontSize(12);
        }
        $this->fpdi->Text(33, 101, $type);

        $this->fpdi->SetFontSize(14);
        $this->fpdi->Text(120, 101, $amount);
        $this->fpdi->Text(33, 112, $price);
        $this->fpdi->Text(120, 112, $this->order->deposit);
    }

    private function setRemark()
    {
        $maxTarget = 55;
        $remark = preg_replace("/\r|\n/", " ", trim($this->order->remark));
        if (strlen($remark) > $maxTarget) {
            preg_match_all('/./u', $remark, $matches);
            for ($i = 0; $i <= (count($matches[0]) / $maxTarget); $i++) {
                $value = "";
                $j_max = count($matches[0]) - ($maxTarget * $i) > $maxTarget ? $maxTarget : count($matches[0]) - ($maxTarget * $i);
                for ($j = 0; $j < $j_max; $j++) {
                    $value .= $matches[0][($maxTarget * $i) + $j];
                }
                $this->fpdi->Text(33, 122 + (4 * $i), $value);
            }

        } else {
            $this->fpdi->Text(33, 122, $remark);
        }
    }

    private function setCaseContent()
    {
        
    }
}