
@extends('masterpage')

@section('content')



<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Return / Replace</h6>
					</div>
					<div class="widget_content">
					<div class="form_grid_12">
						<form name="searchform" onSubmit="return dosearch();">

						<label for="field_title" >Voucher No:</label>
						
							<input type="text" id="tags">
							<div class="btn_30_dark">
									<a href="/IMS/home/return">Search</span></a>
							</div>
							
							
						</form>
						</div>
					</div>
				</div>
</div></div>





	<div id="content">
		<div class="grid_container">
			<div class="grid_12">
				<div class="widget_wrap">
					
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">

<div class="clear"></div>

							
								<span class="clear"></span>
								
								
								<div class="grid_6 invoice_to">
									<ul>
										<li>
										<strong><span>Supplier Name:</span></strong>
										<span>Supplier Bill No:</span>
										<span>Address:</span>
										
										
										</li>
									</ul>
								</div>
								<div class="grid_6 invoice_from">
									<ul>
										<li>
										<strong><span>Voucher No:</span></strong>
										<span>Challan No:</span>									
										<span>Purchase Date:</span>
									
										</li>
									</ul>
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<table>
										<thead>
										<tr class=" gray_sai">
											<th>
												Item Name
											</th>
											<th>
												Quantity
											</th>
											<th>
												Measurement Unit
											</th>
											<th>
												Rate
											</th>
											
											<th>
												Amount(BDT)
											</th>
										</tr>
										</thead>
										<tbody>
										
										<tr>
											<td class="center"></td>
											<td class="center"></td>
											<td class="center"></td>
											<td class="center"></td>
											<td class="center"></td>
										</tr>
											
																				
									
										
										<tr>
											<td colspan="4" class="grand_total">
												Sub Total:
											</td>
											<td>
												
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Discount:
											</td>
											<td>
												
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Others Exp.:
											</td>
											<td>
												
											</td>
										</tr>
										
										<tr>
											<td colspan="4" class="grand_total">
												Gross Total:
											</td>
											<td>
												
											</td>
										</tr>
										</tbody>
										</table>
									</div>
									<p class="amount_word">
										Amounts in word: <span> Taka Only</span>
									</p>
								
								</div>
								<span class="clear"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
	
@endsection