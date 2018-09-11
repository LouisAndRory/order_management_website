@extends('master')

@section('content')
<form action="/">
    @method('post')
    @csrf
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderName">訂購人</label>
            <input type="text" class="form-control" id="orderName" placeholder="請輸入訂購人">
        </div>
        <div class="form-group col-md-6">
            <label for="orderName">聯絡電話</label>
            <input type="tel" class="form-control" id="orderPhone" placeholder="請輸入聯絡電話">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderNameBackup">備用聯絡人</label>
            <input type="text" class="form-control" id="orderNameBackup" placeholder="請輸入備用聯絡人">
        </div>
        <div class="form-group col-md-6">
            <label for="orderPhoneBackup">備用聯絡電話</label>
            <input type="tel" class="form-control" id="orderPhoneBackup" placeholder="請輸入備用聯絡電話">
        </div>
    </div>

    <div class="form-group">
        <label for="orderEmail">電子信箱</label>
        <input type="email" class="form-control" id="orderEmail" placeholder="請輸入電子信箱">
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderDeposit">訂金</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="fa fa-dollar input-group-text"></span>
                </div>
                <input type="number" class="form-control" id="orderDeposit" placeholder="請輸入訂金">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="orderExtraFee">額外費用</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="fa fa-dollar input-group-text"></span>
                </div>
                <input type="number" class="form-control" id="orderExtraFee" placeholder="請輸入額外費用">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderEngagedDate">訂婚日期</label>
            <input type="date" class="form-control" id="orderEngagedDate" placeholder="請輸入訂婚日期">
        </div>
        <div class="form-group col-md-6">
            <label for="orderMarriedDate">結婚日期</label>
            <input type="date" class="form-control" id="orderMarriedDate" placeholder="請輸入結婚日期">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="orderCardRequired">結婚小卡</label>
            <input type="text" class="form-control" id="orderCardRequired" placeholder="">
        </div>
        <div class="form-group col-md-6">
            <label for="orderWoodRequired">準備木盛</label>
            <input type="text" class="form-control" id="orderWoodRequired" placeholder="">
        </div>
    </div>

    <div class="form-group">
        <label for="orderRemark">備註</label>
        <textarea name="Remark" class="form-control" rows="3" placeholder="請輸入備註事項"></textarea>
    </div>
</form>
@endsection