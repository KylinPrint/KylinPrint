<?php

namespace App\Admin\Metrics;

use App\Models\Printer;
use Dcat\Admin\Admin;
use Dcat\Admin\Support\JavaScript;
use Dcat\Admin\Widgets\Metrics\Donut;

class TestPie extends Donut
{
    protected $labels = ['HP', 'Canon','Brother','FUJI'];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [$color->dark90(),$color->blue1(), $color->alpha('blue2', 0.5),$color->blue2()];

        $this->title('New Printer');
        $this->subTitle('Last 100 days');
        $this->chartLabels($this->labels);
        // 设置图表颜色
        $this->chartColors($colors);

        //显示图标百分百
        $this->chart([
            'dataLabels' => [
                'enabled' => true,
                'formatter' => JavaScript::make(
                    <<<JS
                    function (val,options){
                        return val.toFixed(1)+'%';
                    }
                    JS
                )]
            ]);
    }

    /**
     * 渲染模板
     *
     * @return string
     */
    public function render()
    {
        $this->fill();

        return parent::render();
    }

    /**
     * 写入数据.
     *
     * @return void
     */
    public function fill()
    {
        $curTime = now();
        $curTime100DayBefor = now()->subDays(100);

        $HPNum = count(Printer::where('brands_id','=','187')->whereBetween('created_at',[$curTime100DayBefor,$curTime])->get());
        $CanonNum = count(Printer::where('brands_id','=','157')->whereBetween('created_at',[$curTime100DayBefor,$curTime])->get());
        $BrotherNum = count(Printer::where('brands_id','=','156')->whereBetween('created_at',[$curTime100DayBefor,$curTime])->get());
        $FUJINum = count(Printer::where('brands_id','=','173')->whereBetween('created_at',[$curTime100DayBefor,$curTime])->get());

        $exp = Printer::where('id','=','581')->pluck('created_at');
      
        
        $this->withContent($HPNum, $CanonNum, $BrotherNum, $FUJINum);

        // 图表数据

        $this->withChart([$HPNum, $CanonNum, $BrotherNum, $FUJINum]);

    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart(['series' => $data]);
    }

    /**
     * 设置卡片头部内容.
     *
     * @param mixed $desktop
     * @param mixed $mobile
     *
     * @return $this
     */
    protected function withContent($HPNum, $CanonNum, $BrotherNum, $FUJINum)
    {
        $HPColor = Admin::color()->dark90();
        $blue = Admin::color()->alpha('blue2', 0.5);
        $blue1 = Admin::color()->blue1();
        $blue2 = Admin::color()->blue2();

        $style = 'margin-bottom: 8px';
        $labelWidth = 120;

        return $this->content(
            <<<HTML
<div class="d-flex pl-1 pr-1 pt-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $HPColor"></i> {$this->labels[0]}
    </div>
    <div>{$HPNum}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue1"></i> {$this->labels[1]}
    </div>
    <div>{$CanonNum}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue"></i> {$this->labels[2]}
    </div>
    <div>{$BrotherNum}</div>
</div>
<div class="d-flex pl-1 pr-1" style="{$style}">
    <div style="width: {$labelWidth}px">
        <i class="fa fa-circle" style="color: $blue2"></i> {$this->labels[3]}
    </div>
    <div>{$FUJINum}</div>
</div>
HTML
        );
    }
}
