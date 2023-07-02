@extends('layouts.master')
@section('title')
 اضافة فاتورة جديدة
@stop
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto"> الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة فاتورة</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row">
                    @if (session()->has('Add'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session()->get('Add') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                    <div class="col-xl-12">
						<div class="card">
                           <div class="card-body">
                                <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                                {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">رقم الفاتورة</label>
                                                <input type="text" name="invoice_number" id="invoice_number" class="form-control" title="يرجي ادخال رقم الفاتورة" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                                <label>تاريخ الفاتورة </label>
                                                <input type="date" name="invoice_date" id="invoice_date" class="form-control" value="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                                <label>تاريخ الاستحقاق </label>
                                                <input type="date" name="due_date" id="due_date" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                                <label>القسم</label>
                                                <select name="section" id="section" class="form-control SlectBox" onclick="console.log($(this).val())"
                                                onchange="console.log('change is firing')">
                                                    <option value="" selected disabled>حدد قسم</option>
                                                    @foreach ($sections as $section)
                                                    <option value="{{$section->id}}">{{$section->section_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="form-group">
                                                <label for="inputName" class="control-label">المنتج </label>
                                                <select name="product" id="product" class="form-control">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                            <input type="text" class="form-control" id="inputName" name="amount_collection"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="inputName" class="control-label">مبلغ العمولة</label>
                                            <input type="text" class="form-control form-control-lg" id="amount_commission"
                                                name="amount_commission" title="يرجي ادخال مبلغ العمولة "
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                required>
                                        </div>
                                        <div class="col">
                                            <label for="inputName" class="control-label">الخصم</label>
                                            <input type="text" class="form-control form-control-lg" id="discount" name="discount"
                                                title="يرجي ادخال مبلغ الخصم "
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                value=0 required>
                                        </div>
            
                                        <div class="col">
                                            <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                            <select name="rate_vat" id="rate_vat" class="form-control" onchange="myFunction()">
                                                <!--placeholder-->
                                                <option value="" selected disabled>حدد نسبة الضريبة</option>
                                                <option value="5%">5%</option>
                                                <option value="10%">10%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                            <input type="text" class="form-control" id="value_vat" name="value_vat" readonly>
                                        </div>
            
                                        <div class="col">
                                            <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                            <input type="text" class="form-control" id="total" name="total" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label for="exampleTextarea">ملاحظات</label>
                                            <textarea class="form-control" id="exampleTextarea" name="note" rows="3"></textarea>
                                        </div>
                                    </div><br>
                                    <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                    <h5 class="card-title">المرفقات</h5>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <input type="file" name="pic" class="dropify" accept=".pdf,.jpg, .png, image/jpeg, image/png"
                                                data-height="70" />
                                        </div><br>
                                    </div>
                                    

                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                                    </div>
                                </form>
                           </div>
                        </div>
                    </div>
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<script>
    $(document).ready(function() {
            $('select[name="section"]').on('change', function(){
                var SectionId = $(this).val();
                if(SectionId){
                    $.ajax({
                        url: "{{URL::to('section')}}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name=product]').empty();
                            $.each(data,function(key,value){
                                $('select[name=product]').append('<option value="'+value+'">' +value+ '</option>'); 
                            });
                        }
                    });
                }else{
                    console.log('AJAX did not  work');
                }
            })
        });
</script>
<script>
    function myFunction(){
        var amount_commission = parseFloat(document.getElementById('amount_commission').value);
        var discount = parseFloat(document.getElementById('discount').value);
        var rate_vat = parseFloat(document.getElementById('rate_vat').value);
        var value_vat = parseFloat(document.getElementById('value_vat').value);
         var amount_commission2 = amount_commission - discount;
         if(typeof amount_commission === 'undefined' || !amount_commission){
            alert('يرجي ادخال مبلغ العمولة ');
         }
         else{
            var intResults = amount_commission2 * rate_vat / 100;
            var intResults2 = parseFloat(intResults + amount_commission2);
            sumq = parseFloat(intResults).toFixed(2);
            sumt = parseFloat(intResults2).toFixed(2);
            document.getElementById("value_vat").value = sumq;
            document.getElementById("total").value = sumt;
         }
    }
</script>
@endsection