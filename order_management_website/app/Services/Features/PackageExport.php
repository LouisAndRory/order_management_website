<?php

namespace App\Services\Features;


use App\Models\Cookie;
use App\Models\Package;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class PackageExport
{
    private $input;

    public function __construct($input = [])
    {
        $this->input = $input;
    }

    public function download()
    {
        $whereInColumns = ['packages.id', 'pc.case_id'];
        $whereInValues = [];
        foreach ($this->input as $packageId => $cases) {
            foreach ($cases as $caseId) {
                array_push($whereInValues, array('packages.id' => $packageId, 'pc.case_id' => $caseId));
            }
        }

        $packageResult = Package::leftJoin('package_has_cases as pc', 'packages.id', '=', 'pc.package_id')
            ->leftjoin('cases as c', 'pc.case_id', '=', 'c.id')
            ->leftjoin('case_has_cookies as cc', 'pc.case_id', '=', 'cc.case_id')
            ->leftjoin('case_types as ct', 'c.case_type_id', '=', 'ct.id')
            ->leftjoin('packs', 'packs.id', '=', 'cc.pack_id')
            ->leftjoin('cookies', 'cookies.id', '=', 'cc.cookie_id')
            ->leftjoin('orders as o', 'o.id', '=', 'packages.order_id')
            ->groupBy('packages.order_id', 'pc.case_id', 'cc.cookie_id', 'cc.pack_id')
            ->whereNotNull('cookies.id')
            ->where(function ($q) use ($whereInColumns, $whereInValues) {
                foreach ($whereInValues as $valueArray) {
                    $q->orWhere(function ($q) use ($whereInColumns, $valueArray) {
                        foreach ($whereInColumns as $column) {
                            $q->where($column, '=', $valueArray[$column]);
                        }
                    });
                }
            })
            ->select(['cookies.id as cookie_id',
                'cookies.type as cookie_type',
                'cookies.name as cookie_name',
                'cookies.slug as cookie_slug',
                DB::raw('SUM(cc.amount * pc.amount) as total'),
                DB::raw('MIN(packages.arrived_at) as order_arrived_at'),
                'packs.slug as pack_name',
                'ct.slug as case_name',
                'ct.id as case_id',
                'packages.order_id as order_id',
                'o.name as order_name',
                'o.phone as order_phone',
                'o.card_required as order_card_required',
            ])
            ->orderBy('order_arrived_at', 'asc')
            ->orderBy('order_id', 'asc')
            ->orderBy('case_id', 'asc')
            ->orderBy('cookie_id', 'asc')
            ->get();

        $cookiesItem = Cookie::select(['id', 'name'])
            ->where('type', '=', 0)
            ->get();


        Excel::load(storage_path('app/excel/report.xlsx'), function ($doc) use ($packageResult, $cookiesItem) {
            $cookiesTotal = count($cookiesItem);
            // type1->磅蛋糕， type2->瑪德蓮，type3->糖果(牛扎糖，法式巧克力，喜字)，type4->其他（養生薄餅，古早味乾麵，黑芝麻燕麥）
            $cookiesDic = array('type1' => $cookiesTotal + 8,
                'type2' => $cookiesTotal + 9,
                'type3' => $cookiesTotal + 10,
                'type4' => $cookiesTotal + 11,
            );
            foreach ($cookiesItem as $index => $cookie) {
                $id = $cookie->id;
                $cookiesDic[$id] = $index + 8;
            }

            $sheet = $doc->getSheetByName('sheet1'); // sheet with name data, but you can also use sheet indexes.
            $col = -1;
            $oldCaseId = 0;
            $oldOrderId = 0;
            $oldCookieId = 0;
            foreach ($packageResult as $package) {
                $change_col = false;
                if ($oldCaseId != $package->case_id || $oldOrderId != $package->order_id) {
                    $col += 1;
                    $col = ($col % 7 == 0) ? $col + 2 : $col;
                    $colOrderItem = \PHPExcel_Cell::stringFromColumnIndex($col);
                    $sheet->setCellValue($colOrderItem . '1', $package->order_name);
                    $sheet->setCellValue($colOrderItem . '2', $package->order_phone);
                    $sheet->setCellValue($colOrderItem . '5', $package->order_card_required ? '要' : '不要');
                    $sheet->setCellValue($colOrderItem . '3', $package->order_arrived_at);
                    $sheet->getStyle($colOrderItem . '3')->getFont()->setSize(17);
                    $sheet->setCellValue($colOrderItem . '4', $package->case_name);
                    $oldCaseId = $package->case_id;
                    $oldOrderId = $package->order_id;
                    if ($col % 7 == 2) {
                        $colTitle = \PHPExcel_Cell::stringFromColumnIndex($col - 1);
                        foreach ($cookiesItem as $index => $cookie) {
                            $sheet->setCellValue($colTitle . ($index + 8), $cookie->name);
                        }
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 8), '磅蛋糕');
                        $sheet->getRowDimension($cookiesTotal + 8)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 8))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 9), '瑪德蓮');
                        $sheet->getRowDimension($cookiesTotal + 9)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 10), '糖果');
                        $sheet->getRowDimension($cookiesTotal + 10)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 10))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 11), '其他');
                        $sheet->getRowDimension($cookiesTotal + 11)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 11))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }
                    $change_col = true;
                }

                $colCookieItem = \PHPExcel_Cell::stringFromColumnIndex($col);
                $cookieID = $package->cookie_id;
                $needOldValue = true;
                if ($package->cookie_type != 0) {
                    $row = $cookiesDic['type' . $package->cookie_type];
                } else {
                    $row = $cookiesDic[$cookieID];
                    if ($cookieID == $oldCookieId && !$change_col) {

                        $needOldValue = true;
                    } else {
                        $needOldValue = false;
                    }

                }
                $oldCookieId = $cookieID;
                $value = ($package->total) . ($package->pack_name) . ($package->cookie_slug);
                $this->__arrangeCookieName($sheet, $colCookieItem, $row, $value, $needOldValue, $package->cookie_type);

            }


        })->download('xls');
    }

    public function __arrangeCookieName($sheet, $colString, $row, $value, $needOldValue, $cookieType)
    {

        if ($needOldValue) {
            $prevValue = trim($sheet->getCell($colString . $row)->getValue());
            $value = $prevValue . PHP_EOL . $value;
        }

        if ($cookieType != 0 && strlen($prevValue) > 9) {
            $sheet->getStyle($colString . $row)->getAlignment()->setWrapText(true);
        }

        $sheet->setCellValue($colString . $row, $value);


    }
}