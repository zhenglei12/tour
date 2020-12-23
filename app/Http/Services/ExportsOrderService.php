<?php


namespace App\Http\Services;

use App\Http\Model\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportsOrderService implements FromCollection, WithHeadings, WithStyles
{
    use Exportable;

    private $data;

    private $row;

    private $t_row;

    private $st_row;

    private $s_row;

    public function __construct($id)
    {
        $this->setData($id);
    }

    public function headings(): array
    {
        return ['计划确认书'];
    }

    private function setData($id)
    {
        $order = Order::where('id', $id)->with('orderStaff', 'orderTrip', 'orderTripInfo')->first();
        $this->data = [
            ['录单日期', "", $order['enter_date'], '', '制单人', $order['name']],
            ["游玩地区", $order['area'], "", "路线名称", $order->orderTrip['name'] ?? '', "", "VIP卡号", $order['vip_card']],
            ["总团费", $order['tour_fee_amount'], "定金", $order['deposit_amount'], "尾款金额", $order['balance_amount'], "代收款", $order['collection_amount']],
            ["跟团日期", $order['up_group_date'], "离团日期", $order['off_group_date'], "人数", $order['numbers'], "", ""],
            ["时间", "行程安排", "", "", "", "", "用餐", "住宿"],
        ];
        if ($order->orderTripInfo) {
            foreach ($order->orderTripInfo as $key => $v) {
                array_push($this->data, [$v['date'], $v['content'], "", "", "", "", $v['meal'], $v['stay']]);
                $this->s_row[$key] = count($this->data) + 1;
            }
        }
        array_push($this->data, ["姓名", "证件号码", "", "", "", "", "联系电话", "住宿"]);
        $this->st_row = count($this->data) + 1;
        if ($order->orderStaff) {
            foreach ($order->orderStaff as $key => $v) {
                array_push($this->data, [$v['name'], $v['id_crad'], "", "", "", $v['phone'], $v['type'], $v['card_type']]);
                $this->t_row[$key] = count($this->data) + 1;
            }
        }
        array_push($this->data, ["机票信息"]);
        array_push($this->data, ["", "接站日期", "2020-1-1", "航班号", "SJKJKKL", "", "", ""]);
        array_push($this->data, ["", "送站日期", "2020-1-1", "航班号", "SJKJKKL", "", "", ""]);
        array_push($this->data, ["备注", "", "", "", "", "", "", "", "",]);
        $this->row = count($this->data) + 1;
    }

    public function collection()
    {
//        $data = [
//            ['录单日期', "", '2020-12-1', '', '制单人', "ceshi"],
//            ["游玩地区", "北京", "", "路线名称", "测试路线", "", "VIP卡号", "ssssdxxxxxx11222"],
//            ["总团费", "500", "定金", "11", "尾款金额", "200", "代收款", "dasd"],
//            ["跟团日期", "2020-20-1", "离团日期", "2020-20-12", "人数", "200", "", ""],
//            ["时间", "行程安排", "", "", "", "", "用餐", "住宿"],
//            ["9月09号", "去干嘛行程安排撒的撒的身上多多少少撒打算打算速度速度速度速度颠三倒四的十四大去干嘛行程安排撒的撒的身上多多少少撒打算打算速度速度速度速度颠三倒四的十四大去干嘛行程安排撒的撒的身上多多少少撒打算打算速度速度速度速度颠三倒四的十四大去干嘛行程安排撒的撒的身上多多少少撒打算打算速度速度速度速度颠三倒四的十四大", "", "", "", "", "早餐", "自理"],
//            ["姓名", "证件号码", "", "", "", "联系电话", "住宿"],
//            ["张三", "4212224113212421", "", "", "", "14191819198", "成人", "身份证"],
//            ["机票信息"],
//            ["", "接站日期", "2020-1-1", "航班号", "SJKJKKL", "", "", ""],
//            ["", "送站日期", "2020-1-1", "航班号", "SJKJKKL", "", "", ""],
//            ["备注", "", "", "", "", "", "", "", "",],
//        ];
        return collect($this->data);
    }

    public function styles(Worksheet $sheet)
    {
        $this->defaultStyle($sheet);

        $sheet->getRowDimension(1)->setRowHeight(40);//设置第一行行高
        $sheet->getRowDimension($this->row)->setRowHeight(60);//设置第一行行高

        $sheet->getStyle('A1:H1')->getFont()->setSize(20)->setBold(true);
        $sheet->mergeCells("A1:H1"); //合并表第一行
        $sheet->mergeCells("A2:B2"); //合并
        $sheet->mergeCells("B6:F6"); //合并

        if (count($this->s_row) > 0) {
            foreach ($this->s_row as $value) {
                $sheet->mergeCells("B" . $value . ":F" . $value); //合并
            }
        }

        if (count($this->t_row) > 0) {
            foreach ($this->t_row as $value) {
                $sheet->mergeCells("B" . $value . ":F" . $value); //合并
            }
        }

        $sheet->mergeCells("B" . $this->st_row . ":E" . $this->st_row); //合并

        $sheet->mergeCells("B9:E9"); //合并
        $sheet->mergeCells("B" . $this->row . ":H" . $this->row); //合并表第一行

    }

    public function defaultStyle(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(30);//设置默认行高
        $sheet->getDefaultColumnDimension()->setWidth(12);//设置默认的
        $sheet->getStyle('A1:H' . $this->row)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:H' . $this->row)->getAlignment()->setVertical('center');//设置第一行垂直居中
        $sheet->getStyle("A1:H" . $this->row)->getAlignment()->setHorizontal('center');//设置垂直居中
        $styles = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:h' . $this->row)->applyFromArray($styles);
    }
}

