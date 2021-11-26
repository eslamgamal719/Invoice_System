@extends('layouts.master')
@section('title')
قائمة الفواتير 
@endsection
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
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة الفواتير</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session()->has('add'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('add') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('edit'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('edit') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('delete'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('delete') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session()->has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{ session()->get('error') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif


<!-- row -->
<div class="row">

	<!--div-->
	<div class="col-xl-12">
		<div class="card mg-b-20">
			<div class="card-header pb-0">
			@can('اضافة فاتورة')
			<a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
				class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
			@endcan	
			</div>
			<div class="card-body">

			<div class="example">
						<div class="panel panel-primary tabs-style-3">
							<div class="tab-menu-heading">
								<div class="tabs-menu ">
									<!-- Tabs -->
									<ul class="nav panel-tabs">
										<li class=""><a href="#tab11" class="active" data-toggle="tab"><i class="fa fa-laptop"></i>معلومات الفاتورة</a></li>
										<li><a href="#tab12" data-toggle="tab"><i class="fa fa-cube"></i>حالات الدفع</a></li>
										<li><a href="#tab13" data-toggle="tab"><i class="fa fa-cogs"></i>المرفقات</a></li>
									</ul>
								</div>
							</div>
							<div class="panel-body tabs-menu-body">
								<div class="tab-content">
									<div class="tab-pane active" id="tab11">
										<div class="table-responsive">
											<table class="table table-striped table-bordered" style="text-align: center;">
											<tbody>
													<tr>
														<th class="font-weight-bold">رقم الفاتورة</th>
														<td>{{ $invoice->invoice_number }}</td>
														<th class="font-weight-bold">تاريخ الاصدار</th>
														<td>{{ $invoice->invoice_date }}</td>
														<th class="font-weight-bold">تاريخ الاستحقاق</th>
														<td>{{ $invoice->due_date }}</td>
														<th class="font-weight-bold">القسم</th>
														<td>{{ $invoice->section->section_name }}</td>
													</tr>
													<tr>
														<th class="font-weight-bold">المنتج</th>
														<td>{{ $invoice->invoice_number }}</td>
														<th class="font-weight-bold">مبلغ التحصيل</th>
														<td>{{ $invoice->amount_collection }}</td>
														<th class="font-weight-bold">مبلغ العمولة</th>
														<td>{{ $invoice->amount_commission }}</td>
														<th class="font-weight-bold">الخصم</th>
														<td>{{ $invoice->discount }}</td>
													</tr>
													<tr>
														<th class="font-weight-bold">نسبة الضريبة</th>
														<td>{{ $invoice->rate_vat }}</td>
														<th class="font-weight-bold">قيمة الضريبة</th>
														<td>{{ $invoice->value_vat }}</td>
														<th class="font-weight-bold">الاجمالى مع الضريبة</th>
														<td>{{ $invoice->total }}</td>
														<th class="font-weight-bold">الحالة</th>
														
															@if ($invoice->value_status == 1)
															<td>
																<span class="badge badge-pill badge-success">{{ $invoice->status }}</span>
															</td>
															@elseif ($invoice->value_status == 2)
															<td>
																<span class="badge badge-pill badge-danger">{{ $invoice->status }}</span>
															</td>
															@elseif ($invoice->value_status == 3)
															<td>
																<span class="badge badge-pill badge-warning">{{ $invoice->status }}</span>
															</td>
															@endif
														
													</tr>
													<tr>
														<th class="font-weight-bold">ملاحظات</th>
														<td colspan="7">{{ $invoice->note }}</td>
													</tr>

												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane" id="tab12">
										<div class="table-responsive">
											<table class="table table-striped" style="text-align: center;">
												<thead>
													<tr>
														<th class="border-bottom-0">#</th>
														<th class="border-bottom-0">رقم الفاتورة</th>
														<th class="border-bottom-0">نوع المنتج</th>
														<th class="border-bottom-0">القسم</th>
														<th class="border-bottom-0">حالة الدفع</th>
														<th class="border-bottom-0">تاريخ الدفع</th>
														<th class="border-bottom-0">ملاحظات</th>
														<th class="border-bottom-0">تاريخ الاضافه</th>
														<th class="border-bottom-0">المستخدم</th>
													</tr>
												</thead>
												<tbody>
												@foreach($details as $detail)
													<tr>
														<td>{{ $loop->iteration }}</td>
														<td>{{ $detail->invoice_number }}</td>
														<td>{{ $detail->product }}</td>
														<td>{{ $invoice->section->section_name }}</td>
														<td>
															@if ($detail->value_status == 1)
																<span class="badge badge-success">{{ $detail->status }}</span>
															@elseif($detail->value_status == 2)
																<span class="badge badge-danger">{{ $detail->status }}</span>
															@else
																<span class="badge badge-warning">{{ $detail->status }}</span>
															@endif
														</td>
														<td>{{ $detail->payment_date }}</td>
														<td>{{ $detail->note }}</td>
														<td>{{ $detail->created_at }}</td>
														<td>{{ $detail->user }}</td>
													</tr>
												@endforeach	
												</tbody>
											</table>
										</div>
									</div>
									<div class="tab-pane" id="tab13">

										<!--المرفقات-->
											<div class="card-body">
												<p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
												<h5 class="card-title">اضافة مرفقات</h5>
												<form method="post" action="{{ url('/invoice_attachments') }}"
													enctype="multipart/form-data">
													{{ csrf_field() }}
													<div class="custom-file">
														<input type="file" class="custom-file-input" id="customFile"
															name="file_name" required>
														<input type="hidden" id="customFile" name="invoice_number"
															value="{{ $invoice->invoice_number }}">
														<input type="hidden" id="invoice_id" name="invoice_id"
															value="{{ $invoice->id }}">
														<label class="custom-file-label" for="customFile">حدد
															المرفق</label>
													</div><br><br>
													<button type="submit" class="btn btn-primary btn-sm "
														name="uploadedFile">تاكيد</button>
												</form>
											</div>
										<!--المرفقات-->
										<br>


										<div class="table-responsive">
											<table class="table table-striped" style="text-align: center;">
												<thead>
													<tr>
														<th class="border-bottom-0">#</th>
														<th class="border-bottom-0">اسم الملف</th>
														<th class="border-bottom-0">قام بالاضافه</th>
														<th class="border-bottom-0">تاريخ الاضافه</th>
														<th class="border-bottom-0">العمليات</th>
													</tr>
												</thead>
												<tbody>
												@foreach ($attachments as $attachment)
													<tr>
														<td>{{ $loop->iteration }}</td>
														<td>{{ $attachment->file_name }}</td>
														<td>{{ $attachment->created_by }}</td>
														<td>{{ $attachment->created_at }}</td>
														<td>

														@can('عرض المرفق')
														<a class="btn btn-outline-success btn-sm" target="_blank"
														href="{{ url('/view_file/' . $invoice->invoice_number . '/' . $attachment->file_name) }}"
														role="button"><i class="fas fa-eye"></i>&nbsp;
														عرض</a>
														@endcan

														@can('تحميل المرفق')
														<a class="btn btn-outline-info btn-sm"
														href="{{ url('download') }}/{{ $invoice->invoice_number }}/{{ $attachment->file_name }}"
														role="button"><i
															class="fas fa-download"></i>&nbsp;
														تحميل</a>
														@endcan

														@can('حذف المرفق')
														<button class="btn btn-outline-danger btn-sm"
															data-toggle="modal"
															data-file_name="{{ $attachment->file_name }}"
															data-invoice_number="{{ $attachment->invoice_number }}"
															data-id_file="{{ $attachment->id }}"
															data-target="#delete_file">حذف</button>
														@endcan
														</td>
													</tr>
												@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div>
	</div>
	<!--/div-->			
</div>
<!-- row closed -->


    <!-- delete -->
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('delete_file') }}" method="post">
                    {{ csrf_field() }}

                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        </p>

                        <input type="hidden" name="id_file" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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
        $('#delete_file').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id_file = button.data('id_file')
            var file_name = button.data('file_name')
            var invoice_number = button.data('invoice_number')
            var modal = $(this)
            modal.find('.modal-body #id_file').val(id_file);
            modal.find('.modal-body #file_name').val(file_name);
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })
    </script>
@endsection