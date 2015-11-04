<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');
Route::any('view', 'SearchController@searchreturn');
Route::any('/', 'HomeController@index');
Route::get('dailycollection', 'HomeController@dailycollection');
Route::get('printcollection', 'HomeController@printcollection');
Route::any('home/search', 'HomeController@search');
Route::any('home/search/return_item', 'HomeController@return_item');
Route::any('home/search/return_item_ina_to_act', 'HomeController@return_item_ina_to_act');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
Route::any('test', 'TestController@index');
//trailbalance
Route::any('trialbalance', 'TrialbalanceController@index');
Route::any('trialbalance/today', 'TrialbalanceController@today');
Route::any('trialbalance/fromtoday', 'TrialbalanceController@fromtoday');


//masterpage
Route::any('master', 'IndexController@index');
//user groups
Route::any('usersgroup', 'UsersgroupController@index');
Route::any('usersgroup/addnew', 'UsersgroupController@addnew');
Route::any('usersgroup/register', 'UsersgroupController@register');
Route::any('usersgroup/editusersgroup/{id?}', 'UsersgroupController@editusersgroup');
Route::any('usersgroup/delete/{id?}', 'UsersgroupController@delete');
//item groups
Route::any('itemgroup', 'ItemsgroupController@index');
Route::any('itemgroup/addnew', 'ItemsgroupController@addnew');
Route::any('itemgroup/register', 'ItemsgroupController@register');
Route::any('itemgroup/edititemgroup/{id?}', 'ItemsgroupController@edititemgroup');
Route::any('itemgroup/delete/{id?}', 'ItemsgroupController@delete');
//item sub groups
Route::any('itemsubgroup', 'ItemsubgroupController@index');
Route::any('itemsubgroup/addnew', 'ItemsubgroupController@addnew');
Route::any('itemsubgroup/register', 'ItemsubgroupController@register');
Route::any('itemsubgroup/edititemsubgroup/{id?}', 'ItemsubgroupController@edititemsubgroup');
Route::any('itemsubgroup/delete/{id?}', 'ItemsubgroupController@delete');

//itemmaster
Route::any('itemmaster', 'ItemmasterController@index');
Route::any('itemmaster/addnew', 'ItemmasterController@addnew');
Route::any('itemmaster/register', 'ItemmasterController@register');
Route::any('itemmaster/edititemmaster/{id?}', 'ItemmasterController@edititemmaster');
Route::any('itemmaster/delete/{id?}', 'ItemmasterController@delete');

//users
Route::any('users', 'UsersController@index');
Route::any('users/addnew', 'UsersController@addnew');
Route::any('users/changepass', 'UsersController@changepass');
//Users Permission
Route::any('userspermission', 'UserspermissionController@index');
Route::any('/userspermission/privilege/{id?}', 'UserspermissionController@privilege');
Route::any('/userspermission/register', 'UserspermissionController@register');
//suppliers
Route::any('suppliers', 'SuppliersController@index');
Route::any('suppliers/addnew', 'SuppliersController@addnew');
Route::any('suppliers/register', 'SuppliersController@register');
Route::any('suppliers/edit/{id?}', 'SuppliersController@edit');
Route::any('suppliers/delete/{id?}', 'SuppliersController@delete');

//customers
Route::any('customers', 'CustomersController@index');
Route::any('customers/addnew', 'CustomersController@addnew');
Route::any('customers/register', 'CustomersController@register');
Route::any('customers/edit/{id?}', 'CustomersController@edit');
Route::any('customers/delete/{id?}', 'CustomersController@delete');

//measurementgroup
Route::any('measurementgroup', 'MeasurementgroupController@index');
Route::any('measurementgroup/addnew', 'MeasurementgroupController@addnew');
Route::any('measurementgroup/register', 'MeasurementgroupController@register');
Route::any('measurementgroup/edit/{id?}', 'MeasurementgroupController@edit');
Route::any('measurementgroup/delete/{id?}', 'MeasurementgroupController@delete');

//measurementunit
Route::any('measurementunit', 'MeasurementunitController@index');
Route::any('measurementunit/addnew', 'MeasurementunitController@addnew');
Route::any('measurementunit/register', 'MeasurementunitController@register');
Route::any('measurementunit/edit/{id?}', 'MeasurementunitController@edit');
Route::any('measurementunit/delete/{id?}', 'MeasurementunitController@delete');




//purchase
Route::any('purchase', 'PurchaseController@index');
Route::any('purchase/get_purchase_detail', 'PurchaseController@get_purchase_detail');
Route::any('purchase/addnew', 'PurchaseController@addnew');
Route::any('purchase/get_item_by_category', 'PurchaseController@get_item_by_category');
Route::any('purchase/get_item_info', 'PurchaseController@get_item_info');
Route::any('purchase/session_invoice', 'PurchaseController@session_invoice');
Route::any('purchase/invoice_remove', 'PurchaseController@invoice_remove');
Route::any('purchase/register', 'PurchaseController@register');
Route::any('purchase/supplier_register', 'PurchaseController@supplier_register');
Route::any('purchase/view/{id?}', 'PurchaseController@view');
Route::any('purchase/edit/{id?}', 'PurchaseController@edit');
Route::any('purchase/popdf/{id?}', 'PurchaseController@popdf');
Route::any('purchase/pdf/{id?}', 'PurchaseController@pdf');
Route::any('purchase/save_approved', 'PurchaseController@save_approved');
Route::any('purchase/save_unapproved', 'PurchaseController@save_unapproved');
Route::any('purchase/cancel_status', 'PurchaseController@cancel_status');
//billspay
Route::any('billspay', 'BillspayController@index');
Route::any('billspay/addnew', 'BillspayController@addnew');
Route::any('billspay/register', 'BillspayController@register');
Route::any('billspay/edit/{id?}', 'BillspayController@edit');
Route::any('billspay/delete/{id?}', 'BillspayController@delete');
Route::any('billspay/pdf', 'BillspayController@pdf');

//physicalsale
Route::any('physicalsales', 'PhysicalsaleController@index');
Route::any('physicalsales/addnew', 'PhysicalsaleController@addnew');
Route::any('physicalsales/get_customer_info', 'PhysicalsaleController@get_customer_info');
Route::any('physicalsales/customer_register', 'PhysicalsaleController@customer_register');
Route::any('physicalsales/get_item_by_category', 'PhysicalsaleController@get_item_by_category');
Route::any('physicalsales/get_item_info', 'PhysicalsaleController@get_item_info');
Route::any('physicalsales/get_factory_item', 'PhysicalsaleController@get_factory_item');
Route::any('physicalsales/session_invoice', 'PhysicalsaleController@session_invoice');
Route::any('physicalsales/invoice_remove', 'PhysicalsaleController@invoice_remove');
Route::any('physicalsales/register', 'PhysicalsaleController@register');
Route::any('physicalsales/view/{id?}', 'PhysicalsaleController@view');
Route::any('physicalsales/print/{id?}', 'PhysicalsaleController@pdf');
Route::any('physicalsales/challan/{id?}', 'PhysicalsaleController@challan');
Route::any('physicalsales/save_approved', 'PhysicalsaleController@save_approved');
Route::any('physicalsales/save_unapproved', 'PhysicalsaleController@save_unapproved');
Route::any('physicalsales/printtoken/{id?}', 'PhysicalsaleController@printtoken');
Route::any('physicalsales/cancel_status', 'PhysicalsaleController@cancel_status');
//reportpurchases
Route::any('reportpurchases', 'ReportpurchaseController@index');
Route::any('reportpurchases/today', 'ReportpurchaseController@today');
Route::any('reportpurchases/fromtoday', 'ReportpurchaseController@fromtoday');
Route::any('reportpurchases/printpdf/{fromtoday?}/{today?}', 'ReportpurchaseController@printpdf');

//reportlossprofit
Route::any('reportlossprofit', 'ReportlossprofitController@index');
Route::any('reportlossprofit/today', 'ReportlossprofitController@today');
Route::any('reportlossprofit/fromtoday', 'ReportlossprofitController@fromtoday');
Route::any('reportlossprofit/fromtoday/sales/{fromtoday?}/{today?}', 'ReportlossprofitController@fromtodaysalesview');
Route::any('reportlossprofit/print/{id?}', 'ReportlossprofitController@pdf');
Route::any('reportlossprofit/fromtoday/cash/{fromtoday?}/{today?}', 'ReportlossprofitController@fromtodaycashview');

//reportssale
Route::any('reportssale', 'ReportssaleController@index');
Route::any('reportssale/today', 'ReportssaleController@today');
Route::any('reportssale/fromtoday', 'ReportssaleController@fromtoday');
Route::any('reportssale/printpdf/{fromtoday?}/{today?}', 'ReportssaleController@printpdf');


//factoryitem
Route::any('factoryitem', 'FactoryitemController@index');
Route::any('factoryitem/addnew', 'FactoryitemController@addnew');
Route::any('factoryitem/get_item_by_category', 'FactoryitemController@get_item_by_category');
Route::any('factoryitem/register', 'FactoryitemController@register');
Route::any('factoryitem/delete/{id?}', 'FactoryitemController@delete');
Route::any('factoryitem/view/{id?}', 'FactoryitemController@view');
Route::any('factoryitem/view/{id?}/download_csv', 'FactoryitemController@download_csv');
Route::any('factoryitem/view/{id?}/save_feedback', 'FactoryitemController@save_feedback');
Route::any('factoryitem/view/{id?}/remove_feedback', 'FactoryitemController@remove_feedback');
Route::any('factoryitem/remain/{id?}', 'FactoryitemController@remain');
Route::any('factoryitem/remain/{id?}/download_csv_remain', 'FactoryitemController@download_csv_remain');
Route::any('factoryitem/sales/{id?}', 'FactoryitemController@sales');
Route::any('factoryitem/sales/{id?}/download_csv_sales', 'FactoryitemController@download_csv_sales');
Route::any('factoryitem/sales/{id?}/save_feedback', 'FactoryitemController@save_feedback');
Route::any('factoryitem/sales/{id?}/remove_feedback', 'FactoryitemController@remove_feedback');
Route::any('factoryitem/damaged/{id?}', 'FactoryitemController@damaged');
Route::any('factoryitem/damaged/{id?}/download_csv_damaged', 'FactoryitemController@download_csv_damaged');

//factory stock
Route::any('fstokin', 'FstokinController@index');
Route::any('fstokin/today', 'FstokinController@today');
Route::any('fstokin/fromtoday', 'FstokinController@fromtoday');
Route::any('fstokin/printpdf/{fromtoday?}/{today?}', 'FstokinController@printpdf');

//companyprofile
Route::any('companyprofile', 'CompanyprofileController@index');
Route::any('companyprofile/register', 'CompanyprofileController@register');

//admintemplate
Route::any('admintemplate', 'AdminController@index');

//bankinfo
Route::any('bankinfo', 'BankinfoController@index');
Route::any('bankinfo/addnew', 'BankinfoController@addnew');
Route::any('bankinfo/register', 'BankinfoController@register');
Route::any('bankinfo/edit/{id?}', 'BankinfoController@edit');
Route::any('bankinfo/delete/{id?}', 'BankinfoController@delete');

//bankaccount
Route::any('bankaccount', 'BankaccountController@index');
Route::any('bankaccount/addnew', 'BankaccountController@addnew');
Route::any('bankaccount/register', 'BankaccountController@register');
Route::any('bankaccount/edit/{id?}', 'BankaccountController@edit');
Route::any('bankaccount/delete/{id?}', 'BankaccountController@delete');

//coa
Route::any('coa', 'CoaController@index');
Route::any('coa/addnew', 'CoaController@addnew');
Route::any('coa/coatype/addnew', 'CoaController@addnewcoatype');
Route::any('coa/register', 'CoaController@register');
Route::any('coa/coatype/register', 'CoaController@registercoatype');
Route::any('coa/registerbank', 'CoaController@registerbank');
Route::any('coa/customers/register', 'CoaController@registercc');
Route::any('coa/suppliers/register', 'CoaController@registercs');
Route::any('coa/edit/{id?}', 'CoaController@edit');
Route::any('coa/delete/{id?}', 'CoaController@delete');
Route::any('bankaccountcoa/addnew', 'CoaController@addnewbac');
Route::any('bankaccountcoa/get_bankaccount_code', 'CoaController@get_bankaccount_code');
Route::any('customercoa/addnew', 'CoaController@addnewcc');
Route::any('customercoa/get_customer_code', 'CoaController@get_customer_code');
Route::any('suppliercoa/addnew', 'CoaController@addnewcs');
Route::any('suppliercoa/get_supplier_code', 'CoaController@get_supplier_code');


//voucher
Route::any('voucher', 'VoucherController@index');
Route::any('voucher/register', 'VoucherController@register');
Route::any('voucher/registerp', 'VoucherController@registerp');
Route::any('voucher/customers/register', 'VoucherController@registerc');
Route::any('voucher/receive/register', 'VoucherController@registerr');
Route::any('voucher/contra/register', 'VoucherController@registercontra');
Route::any('voucher/bkash/register', 'VoucherController@registerbkash');
Route::any('voucher/sap/register', 'VoucherController@registersap');
Route::any('voucher/kcs/register', 'VoucherController@registerkcs');
Route::any('voucher/mbank/register', 'VoucherController@registermbank');
Route::any('voucher/pdf/{id?}/{type?}', 'VoucherController@pdf');
Route::any('voucher/save_approved', 'VoucherController@save_approved');
Route::any('voucher/save_unapproved', 'VoucherController@save_unapproved');
//contra voucher
Route::any('contravoucher', 'ContravoucherController@index');
Route::any('contravoucher/cashregister', 'ContravoucherController@cashregister');
Route::any('contravoucher/pdf/{id?}/{type?}/{status}', 'ContravoucherController@pdf');
Route::any('contravoucher/bankregister', 'ContravoucherController@bankregister');
Route::any('contravoucher/banktobankregister', 'ContravoucherController@banktobankregister');
//general ledger
Route::any('generalledger', 'LedgerController@index');
Route::any('generalledger/today', 'LedgerController@today');
Route::any('generalledger/bankbook', 'LedgerController@bankbook');

//Bank Book
Route::any('bankbook', 'LedgerController@bankbookview');

//Cash Book

Route::any('cashbook', 'CashController@index');
Route::any('cashbook/today', 'CashController@today');
Route::any('cashbook/fromtoday', 'CashController@fromtoday');


//Customers Ledger
Route::any('customersledger', 'CustomersledgerController@report');
Route::any('customersledger/fromtoday', 'CustomersledgerController@fromtoday');

//Suppliers Ledger
Route::any('suppliersledger', 'SuppliersledgerController@report');
Route::any('suppliersledger/fromtoday', 'SuppliersledgerController@fromtoday');

// Employee
Route::any('employee', 'EmployeeinfoController@index');
Route::any('employee/get_employee_salary', 'EmployeeinfoController@get_employee_salary');
Route::any('employee/addnew', 'EmployeeinfoController@addnew');
Route::any('employee/register', 'EmployeeinfoController@register');
Route::any('employee/edit/{id?}', 'EmployeeinfoController@edit');
Route::any('employee/delete/{id?}', 'EmployeeinfoController@delete');

//Legder Entry
Route::any('ledgerentry', 'PettycashController@index');
Route::any('ledgerentry/addnew', 'PettycashController@addnew');
Route::any('ledgerentry/register', 'PettycashController@register');
Route::any('ledgerentry/edit/{id?}', 'PettycashController@edit');
Route::any('ledgerentry/delete/{id?}', 'PettycashController@delete');
Route::any('ledgerentry/printpdf/', 'PettycashController@printpdf');
Route::any('ledgerentry/pdf/{id?}', 'PettycashController@pdf');

//Employee Salary
Route::any('employeesal', 'EmployeesalaryController@index');
Route::any('employeesal/addnew', 'EmployeesalaryController@addnew');
Route::any('employeesal/addnew/get_employee_salary', 'EmployeesalaryController@get_employee_salary');
Route::any('employeesal/register', 'EmployeesalaryController@register');
Route::any('employeesal/edit/{id?}', 'EmployeesalaryController@edit');
Route::any('employeesal/delete/{id?}', 'EmployeesalaryController@delete');

//PL Accoumt
Route::any('placcount', 'PlaccountController@index');
Route::any('placcount/today', 'PlaccountController@today');
Route::any('placcount/fromtoday', 'PlaccountController@fromtoday');

//Balance Sheet
Route::any('balancesheet', 'BalancesheetController@index');
Route::any('balancesheet/today', 'BalancesheetController@today');
Route::any('balancesheet/fromtoday', 'BalancesheetController@fromtoday');

//Repoert HR and Payroll
Route::any('reports', 'ReportHRandPayrollController@index');
Route::any('reports/datewise', 'ReportHRandPayrollController@datewise');

