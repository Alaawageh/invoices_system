@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
    تقرير العملاء 
@stop
@endsection
@section('page-header')

<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">التقارير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير
                العملاء</span>
        </div>
    </div>
</div>

@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    <div class="row">
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <form action="/search_report" method="POST" role="search" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>القسم</label>
                                    <select name="section" id="section" class="form-control select2" onclick="console.log($(this).val())"
                                    onchange="console.log('change is firing')">
                                        <option value="" selected disabled>حدد قسم</option>
                                        @foreach ($sections as $section)
                                        <option value="{{$section->id}}">{{$section->section_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="inputName" class="control-label">المنتج </label>
                                    <select name="product" id="product" class="form-control">
    
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" id="start_at">
                                <label for="exampleFormControlSelect1">من تاريخ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" value="{{ $start_at ?? '' }}" name="start_at" type="date">
                                </div><!-- input-group -->
                            </div>
                            <div class="col-lg-3" id="end_at">
                                <label for="exampleFormControlSelect1">الى تاريخ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" value="{{ $end_at ?? '' }}" name="end_at" type="date">
                                </div><!-- input-group -->
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-sm-1 col-md-1">
                                <button class="btn btn-primary">بحث</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if(isset($invoices))
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap" style=" text-align: center">
                            <thead>
                                <tr>
                                    <th class=" border-bottom-0">#</th>
                                    <th class=" border-bottom-0">رقم الفاتورة</th>
                                    <th class=" border-bottom-0">تاريخ الفاتورة</th>
                                    <th class=" border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class=" border-bottom-0">المنتج</th>
                                    <th class=" border-bottom-0">القسم</th>
                                    <th class=" border-bottom-0">نسبة الضريبة</th>
                                    <th class=" border-bottom-0">قيمة الضريبة</th>
                                    <th class=" border-bottom-0">الاجمالي</th>
                                    <th class=" border-bottom-0">الحالة</th>
                                    <th class=" border-bottom-0">الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = 0;	
                                @endphp
                                @foreach ($invoices as $invoice)
                                @php
                                    $i++;
                                @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$invoice->invoice_number}}</td>
                                        <td>{{$invoice->invoice_date}}</td>
                                        <td>{{$invoice->due_date}}</td>
                                        <td>{{$invoice->product}}</td>
                                        <td>
                                            <a href="{{url('invoicesDetails')}}/{{$invoice->id}}">{{$invoice->section->section_name}}</a>
                                        </td>
                                        <td>{{$invoice->rate_vat}}</td>
                                        <td>{{$invoice->value_vat}}</td>
                                        <td>{{$invoice->total}}</td>
                                        <td>
                                            @if ($invoice->value_status === 1)
                                                <span class="text-success">{{$invoice->status}}</span>
                                            @elseif($invoice->value_status === 2)
                                                <span class="text-danger">{{$invoice->status}}</span>
                                            @else
                                                <span class="text-warning">{{$invoice->status}}</span>
                                            @endif
                                        </td>
                                        <td>{{$invoice->note}}</td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    

@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
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
@endsection