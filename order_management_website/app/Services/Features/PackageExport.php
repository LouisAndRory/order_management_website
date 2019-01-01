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
            ->leftJoin('cases as c', 'pc.case_id', '=', 'c.id')
            ->leftJoin('case_has_cookies as cc', 'pc.case_id', '=', 'cc.case_id')
            ->leftJoin('case_types as ct', 'c.case_type_id', '=', 'ct.id')
            ->leftJoin('packs', 'packs.id', '=', 'cc.pack_id')
            ->leftJoin('cookies', 'cookies.id', '=', 'cc.cookie_id')
            ->leftJoin('orders as o', 'o.id', '=', 'packages.order_id')
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
                'packs.id as pack_id',
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
            ->where('enabled', '=', true)
            ->get();

        Excel::load(storage_path('app/excel/report.xlsx'), function ($doc) use ($packageResult, $cookiesItem) {
            $allUsedCookieIds = $packageResult->pluck('cookie_id')->unique()->values()->toArray();
            $cookiesItem = $cookiesItem->whereIn('id', $allUsedCookieIds)->values();

            $cookiesTotal = count($cookiesItem);
            // type1->磅蛋糕， type2->瑪德蓮，type3->糖果(牛扎糖，法式巧克力，喜字)，type4->其他（養生薄餅，古早味乾麵，黑芝麻燕麥）
            $cookiesDic = array('type1' => $cookiesTotal + 6,
                'type2' => $cookiesTotal + 7,
                'type3' => $cookiesTotal + 8,
                'type4' => $cookiesTotal + 9,
            );
            foreach ($cookiesItem as $index => $cookie) {
                $id = $cookie->id;
                $cookiesDic[$id] = $index + 6;
            }

            $sheet = $doc->getSheetByName('sheet1'); // sheet with name data, but you can also use sheet indexes.
            $col = -1;
            $oldCaseId = 0;
            $oldOrderId = 0;
            $oldCookieId = 0;

            foreach ($packageResult as $package) {
                $change_col = false;
                if ($oldCaseId !== $package->case_id || $oldOrderId !== $package->order_id) {
                    $col++;
                    $col = ($col % 7 === 0) ? $col + 2 : $col;
                    $colOrderItem = \PHPExcel_Cell::stringFromColumnIndex($col);
                    $sheet->setCellValue($colOrderItem . '1', $package->order_name);
                    $sheet->setCellValue($colOrderItem . '2', $package->order_phone);
                    $sheet->setCellValue($colOrderItem . '3', $package->order_arrived_at);
                    $sheet->getStyle($colOrderItem . '3')->getFont()->setSize(17);
                    $sheet->setCellValue($colOrderItem . '4', $package->case_name);
                    $oldCaseId = $package->case_id;
                    $oldOrderId = $package->order_id;
                    if ($col % 7 === 2) {
                        $colTitle = \PHPExcel_Cell::stringFromColumnIndex($col - 1);
                        foreach ($cookiesItem as $index => $cookie) {
                            $sheet->setCellValue($colTitle . ($index + 6), $cookie->name);
                        }
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 6), '磅蛋糕');
                        $sheet->getRowDimension($cookiesTotal + 6)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 6))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 7), '瑪德蓮');
                        $sheet->getRowDimension($cookiesTotal + 7)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 7))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 8), '糖果');
                        $sheet->getRowDimension($cookiesTotal + 8)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 8))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $sheet->setCellValue($colTitle . ($cookiesTotal + 9), '其他');
                        $sheet->getRowDimension($cookiesTotal + 9)->setRowHeight(-1);
                        $sheet->getStyle($colTitle . ($cookiesTotal + 9))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    }
                    $change_col = true;
                }

                $colCookieItem = \PHPExcel_Cell::stringFromColumnIndex($col);
                $cookieID = $package->cookie_id;
                $needOldValue = true;
                if ($package->cookie_type !== 0) {
                    $row = $cookiesDic['type' . $package->cookie_type];
                } else {
                    $row = $cookiesDic[$cookieID];
                    if ($cookieID === $oldCookieId && !$change_col) {
                        $needOldValue = true;
                    } else {
                        $needOldValue = false;
                    }
                }
                $oldCookieId = $cookieID;
                $value = $package->total . $package->pack_name . $package->cookie_slug;
                $this->__arrangeCookieName($sheet, $colCookieItem, $row, $value, $needOldValue, $package->cookie_type);
            }

            //-- arrange summary
            $summarySheet = $doc->getSheetByName('summary');
            $cookieSummary = [];
            foreach ($cookiesItem as $cookie) {
                array_push($cookieSummary, [
                    'id' => $cookie->id,
                    'name' => $cookie->name,
                    'ingredients' => []
                ]);
            }
            array_push($cookieSummary, ['id' => 'type1', 'name' => '磅蛋糕', 'ingredients' => []]);
            array_push($cookieSummary, ['id' => 'type2', 'name' => '瑪德蓮', 'ingredients' => []]);
            array_push($cookieSummary, ['id' => 'type3', 'name' => '糖果', 'ingredients' => []]);
            array_push($cookieSummary, ['id' => 'type4', 'name' => '其他', 'ingredients' => []]);

            $allPacks = [];
            foreach ($packageResult as $packageIndex => $packageItem) {
                $allPacks[$packageItem->pack_id] = [
                    'name' => $packageItem->pack_name,
                ];
                foreach ($cookieSummary as $index => &$cookieItem) {
                    if ($cookieItem['id'] === $packageItem->cookie_id ||
                        $cookieItem['id'] === 'type' . $packageItem->cookie_type
                    ) {
                        if (str_contains($cookieItem['id'], ['type'])) {
                            if (!array_key_exists($packageItem->pack_id, $cookieItem['ingredients'])) {
                                $cookieItem['ingredients'][$packageItem->pack_id] = [];
                            }

                            array_push($cookieItem['ingredients'][$packageItem->pack_id], [
                                'amount' => $packageItem->total,
                                'cookie_slug' => $packageItem->cookie_slug
                            ]);
                        } else {
                            if (!array_key_exists($packageItem->pack_id, $cookieItem['ingredients'])) {
                                $cookieItem['ingredients'][$packageItem->pack_id] = 0;
                            }

                            $cookieItem['ingredients'][$packageItem->pack_id] += $packageItem->total;
                        }

                        break;
                    }
                }
            }

            //-- arrange packs
            $packStartIndex = 0;
            $packOffsetIndex = 2;
            $packRowOffsetIndex = 1;
            foreach ($allPacks as &$pack) {
                $columnIndex = \PHPExcel_Cell::stringFromColumnIndex($packStartIndex + $packOffsetIndex);
                $pack['columnIndex'] = $columnIndex;
                $summarySheet->setCellValue($columnIndex . $packRowOffsetIndex, $pack['name']);

                $packStartIndex++;
            }

            $startIndex = 2;
            foreach ($cookieSummary as $index => $cookieItem) {
                $summarySheet->setCellValue('B' . ($index + $startIndex), $cookieItem['name']);
                foreach ($cookieItem['ingredients'] as $packId => $amount) {
                    if (str_contains($cookieItem['id'], ['type'])) {
                        $arrangedData = [];
                        foreach ($amount as $value) {
                            if (!array_key_exists($value['cookie_slug'], $arrangedData)) {
                                $arrangedData[$value['cookie_slug']] = 0;
                            }

                            $arrangedData[$value['cookie_slug']] += $value['amount'];
                        }
                        $data = [];
                        foreach ($arrangedData as $slug => $count) {
                            array_push($data, $count . $slug);
                        }
                        $summarySheet->setCellValue($allPacks[$packId]['columnIndex'] . ($index + $startIndex), implode(',', $data));
                    } else {
                        $summarySheet->setCellValue($allPacks[$packId]['columnIndex'] . ($index + $startIndex), $amount);
                    }
                }
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