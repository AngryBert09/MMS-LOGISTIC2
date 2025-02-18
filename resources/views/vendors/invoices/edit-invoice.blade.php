<!DOCTYPE html>
<html>

@include('layout.head')

<body>


    @include('layout.nav')
    @include('layout.right-sidebar')
    @include('layout.left-sidebar')


    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                @include('layout.breadcrumb')

                <!-- horizontal Basic Forms Start -->
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-warning h4">Edit Invoice</h4>
                            <p class="mb-30">Make sure to read carefuly before proceeding</p>
                        </div>

                    </div>
                    <form action="{{ route('invoices.update', $invoice->invoice_id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Indicating that this is an update request -->

                        <div class="form-group">
                            <label>Invoice #</label>
                            <!-- Displaying invoice number -->
                            <input class="form-control" type="text" value="{{ $invoice->invoice_number }}"
                                disabled />
                        </div>
                        <div class="form-group">
                            <label>Invoice Date</label>
                            <!-- Displaying invoice date -->
                            <input class="form-control" value="{{ $invoice->invoice_date }}" type="text" disabled />
                        </div>
                        <div class="form-group">
                            <label>Due Date</label>
                            <!-- Displaying due date -->
                            <input class="form-control" value="{{ $invoice->due_date }}" type="text" disabled />
                        </div>
                        <div class="form-group">
                            <label>Currency Code</label>
                            <!-- Displaying currency code, now editable -->
                            <input class="form-control" name="currency_code" value="UNDER DEVELOPMENT" type="text"
                                readonly />
                        </div>
                        <div class="form-group">
                            <label>Discount Amount</label>
                            <!-- Displaying discount amount, now editable -->
                            <input class="form-control" name="discount_amount" value="{{ $invoice->discount_amount }}"
                                type="number" />
                        </div>
                        <div class="form-group">
                            <label>Tax Amount</label>
                            <!-- Displaying discount amount, now editable -->
                            <input class="form-control" name="tax_amount" value="{{ $invoice->tax_amount }}"
                                type="number" />
                        </div>
                        <div class="form-group">
                            <label>Total Amount</label>
                            <!-- Displaying total amount -->
                            <input class="form-control" value="{{ $invoice->total_amount }}" type="text" disabled />
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <!-- Displaying status -->
                            <input class="form-control" value="{{ $invoice->status }}" type="text" disabled />
                        </div>

                        <!-- Cancel and Update buttons -->
                        <div class="form-group text-left">
                            <!-- Cancel Button -->
                            <a href="{{ route('invoices.index') }}" class="btn btn-danger">Cancel</a>

                            <!-- Update Button -->
                            <button type="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>



                    <div class="collapse collapse-box" id="horizontal-basic-form1">
                        <div class="code-box">
                            <div class="clearfix">
                                <a href="javascript:;" class="btn btn-primary btn-sm code-copy pull-left"
                                    data-clipboard-target="#horizontal-basic"><i class="fa fa-clipboard"></i> Copy
                                    Code</a>
                                <a href="#horizontal-basic-form1" class="btn btn-primary btn-sm pull-right"
                                    rel="content-y" data-toggle="collapse" role="button"><i
                                        class="fa fa-eye-slash"></i> Hide Code</a>
                            </div>
                            <textarea class="form-control"> id="horizontal-basic">
<form>
	<div class="form-group">
		<label>Text</label>
		<input class="form-control" type="text" placeholder="Johnny Brown">
	</div>
	<div class="form-group">
		<label>Email</label>
		<input class="form-control" value="bootstrap@example.com" type="email">
	</div>
	<div class="form-group">
		<label>URL</label>
		<input class="form-control" value="https://getbootstrap.com" type="url">
	</div>
	<div class="form-group">
		<label>Telephone</label>
		<input class="form-control" value="1-(111)-111-1111" type="tel">
	</div>
	<div class="form-group">
		<label>Password</label>
		<input class="form-control" value="password" type="password">
	</div>
	<div class="form-group">
		<label>Readonly input</label>
		<input class="form-control" type="text" placeholder="Readonly input here…" readonly>
	</div>
	<div class="form-group">
		<label>Disabled input</label>
		<input type="text" class="form-control" placeholder="Disabled input" disabled="">
	</div>
	<div class="form-group">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<label class="weight-600">Custom Checkbox</label>
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="customCheck1-1">
					<label class="custom-control-label" for="customCheck1-1">Check this custom checkbox</label>
				</div>
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="customCheck2-1">
					<label class="custom-control-label" for="customCheck2-1">Check this custom checkbox</label>
				</div>
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="customCheck3-1">
					<label class="custom-control-label" for="customCheck3-1">Check this custom checkbox</label>
				</div>
				<div class="custom-control custom-checkbox mb-5">
					<input type="checkbox" class="custom-control-input" id="customCheck4-1">
					<label class="custom-control-label" for="customCheck4-1">Check this custom checkbox</label>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">
				<label class="weight-600">Custom Radio</label>
				<div class="custom-control custom-radio mb-5">
					<input type="radio" id="customRadio4" name="customRadio" class="custom-control-input">
					<label class="custom-control-label" for="customRadio4">Toggle this custom radio</label>
				</div>
				<div class="custom-control custom-radio mb-5">
					<input type="radio" id="customRadio5" name="customRadio" class="custom-control-input">
					<label class="custom-control-label" for="customRadio5">Or toggle this other custom radio</label>
				</div>
				<div class="custom-control custom-radio mb-5">
					<input type="radio" id="customRadio6" name="customRadio" class="custom-control-input">
					<label class="custom-control-label" for="customRadio6">Or toggle this other custom radio</label>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Disabled select menu</label>
		<select class="form-control" disabled="">
			<option>Disabled select</option>
		</select>
	</div>
	<div class="form-group">
		<label>input plaintext</label>
		<input type="text" readonly class="form-control-plaintext" value="email@example.com">
	</div>
	<div class="form-group">
		<label>Textarea</label>
		<textarea class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Help text</label>
                            <input type="text" class="form-control">
                            <small class="form-text text-muted">
                                Your password must be 8-20 characters long, contain letters and numbers, and must not
                                contain spaces, special characters, or emoji.
                            </small>
                        </div>
                        <div class="form-group">
                            <label>Example file input</label>
                            <input type="file" class="form-control-file form-control height-auto">
                        </div>
                        <div class="form-group">
                            <label>Custom file input</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input">
                                <label class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                        </form>

                        </code></pre>
                    </div>
                </div>
            </div>
            <!-- horizontal Basic Forms End -->




            </code></pre>
        </div>
    </div>
    </div>
    <!-- Input Validation End -->
    </div>

    </div>
    </div>

    <!-- js -->
    <script src="{{ asset('js/core.js') }}"></script>
    <script src="{{ asset('js/script.min.js') }}"></script>
    <script src="{{ asset('js/process.js') }}"></script>
    <script src="{{ asset('js/layout-settings.js') }}"></script>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
</body>

</html>
