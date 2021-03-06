<?php

class ExpenseClaimController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (! Entrust::can('view_expense_claim') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		Session::forget('receiptItems');
		Session::forget('receiptDetails');

		$waitingClaims = ExpenseClaim::where('status', 'New')->get();
		$paymentClaims = ExpenseClaim::where('status', 'Approved')->get();
		$settledClaims = ExpenseClaim::where('status', 'Payed')->get();
		$declinedClaims = ExpenseClaim::where('status', 'Declined')->get();

		$receipts = ClaimReceipt::where('status', 'New')->get();

		Audit::logaudit('Expense Claims', 'viewed expense claims', 'viewed expense claims in the system');

		return View::make('expense_claims.index', compact('receipts', 'waitingClaims', 'paymentClaims', 'settledClaims', 'declinedClaims'));
	}
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

        if (! Entrust::can('view_expense_claim') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		$receiptItems = Session::get('receiptItems');
		$receiptDetails = Session::get('receiptDetails');

		Audit::logaudit('Expense Claims', 'viewed expense claim details', 'viewed expense claim details in the system');

		return View::make('expense_claims.newReceipt', compact('receiptDetails', 'receiptItems'));
	}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if (! Entrust::can('update_expense_claim') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		$receipts = ClaimReceipt::where('id', $id)->get();
		$items = ClaimReceiptItem::where('claimReceiptID', $id)->get();

		return View::make('expense_claims.editReceipt', compact('items', 'receipts'));
	}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * Show NEW RECEIPT PAGE
	 */
	public function newReceipt(){
		if (! Entrust::can('create_new_receipt') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		return View::make('expense_claims.newReceipt');
	}
	}


	/**
	 * NEW RECEIPT FUNCTIONALITY
	 */
	public function addReceiptItem(){
		if (! Entrust::can('create_new_receipt') ) // Checks the current user
        {
        return Redirect::to('dashboard')->with('notice', 'you do not have access to this resource. Contact your system admin');
        }else{
		$data = Input::all();

		Session::put('receiptDetails', array(
			/*'claimer' => array_get($data, 'claimer'),*/
			'receiptFrom' => array_get($data, 'receiptFrom'),
			'trDate' => array_get($data, 'trDate')
		));
		
		Session::push('receiptItems', array(
			'description' => array_get($data, 'description'),
			'quantity' => array_get($data, 'quantity'),
			'unitPrice' => array_get($data, 'unit_price')
		));

		return Redirect::action('ExpenseClaimController@show');
	}
	}


	/**
	 * REMOVE ITEM BEFORE SUBMITTING
	 */
	public function removeItem($count){
		$items = Session::get('receiptItems');
		unset($items[$count]);
		$newItems = array_values($items);
		Session::put('receiptItems', $newItems);

		return Redirect::action('ExpenseClaimController@show');
	}


	/**
	 * COMMIT TRANSACTIONS
	 * == ADD RECEIPT & RECEIPT ITEMS TO DB
	 */
	public function commitTransaction(){
		$receipt = Session::get('receiptDetails');
		$items = Session::get('receiptItems');

		if(count($items) <= 0){
			return Redirect::action('ExpenseClaimController@show')->with('error', 'Please add receipt items');
		}

		$total = 0;
		foreach($items as $trItem){
			$total += ($trItem['quantity'] * $trItem['unit_price']);
		}
		
		$claimReceipt = new ClaimReceipt;

		$claimReceipt->receipt_from = $receipt['receiptFrom'];
		$claimReceipt->transaction_date = $receipt['trDate'];
		$claimReceipt->status = 'New';
		$claimReceipt->save();

		$lastReceiptID = $claimReceipt->id;

		foreach($items as $item){
			$claimReceiptItem = new ClaimReceiptItem;

			$claimReceiptItem->claimReceiptID = $lastReceiptID;
			$claimReceiptItem->description = $item['description'];
			$claimReceiptItem->quantity = $item['quantity'];
			$claimReceiptItem->unit_price = $item['unitPrice'];
			$claimReceiptItem->save();
		}

		Session::forget('receiptDetails');
		Session::forget('receiptItems');


		Audit::logaudit('Expense Claims', 'added expense claim receipt', 'added expense claims receipt from user '.$receipt['receiptFrom'].' total amount '.$total.' in the system');

		return Redirect::action('ExpenseClaimController@index')->with('success', 'Receipt successfully created and saved');
		
	}


	/**
	 * SUBMIT RECEIPTS AS CLAIMS FOR APPROVAL
	 */
	public function submitClaim(){
		$receipts_checked = Input::get('receipt');
		if($receipts_checked == ""){
			return Redirect::action('ExpenseClaimController@index')->with('error', 'Please select receipts to submit for approval');
		}else{
			if(is_array($receipts_checked)){
			 	// Create a new expense claim
			 	$expenseClaim = new ExpenseClaim;
			 	$expenseClaim->claimer = Confide::user()->username;
			 	$expenseClaim->date_submitted = date('Y-m-d');
			 	$expenseClaim->status = 'New';
			 	$expenseClaim->save();

			 	// Update claim receipts
			 	$claimID = $expenseClaim->id;
			 	DB::table('claim_receipts')->whereIn('id', $receipts_checked)
			 	    ->update(array('claim_id'=>$claimID, 'status'=>'Awaiting Review'));

			 	Audit::logaudit('Expense Claims', 'submitted expense claim for review', 'submitted expense claim for review for claimer '.Confide::user()->username.' in the system');

			 	return Redirect::action('ExpenseClaimController@index')->with('success', 'Successfully submitted for review.');

			}
		}
	}


	/**
	 * APPROVE EXPENSE CLAIM VIEW
	 */
	public function approveClaimView($id){
		$receipts = ClaimReceipt::where('claim_id', $id)->get();

		return View::make('expense_claims.approveClaim', compact('receipts', 'id'));
	}


	/**
	 * APPROVE CLAIM
	 */
	public function approveClaim($id){
		ClaimReceipt::where('claim_id', $id)->update(array('status'=>'Awaiting Payment'));
		ExpenseClaim::where('id', $id)->update(array('status'=>'Approved'));

		Audit::logaudit('Expense Claims', 'approved expense claim', 'approved expense claim for claimer '.Confide::user()->username.' in the system');

		return Redirect::action('ExpenseClaimController@index')->with('success', 'Claim successfully Approved, Awaiting payment.');
	}


	/**
	 * DECLINED CLAIM
	 */
	public function declineClaim($id){
		ClaimReceipt::where('claim_id', $id)->update(array('status'=>'Declined'));
		ExpenseClaim::where('id', $id)->update(array('status'=>'Declined'));

		Audit::logaudit('Expense Claims', 'declined expense claim', 'declined expense claim for claimer '.Confide::user()->username.' in the system');

		return Redirect::action('ExpenseClaimController@index')->with('success', 'Claim Declined!!!');
	}


	/**
	 * SHOW PAY CLAIM VIEW
	 */
	public function payClaimView($id){
		$fromAccounts = Account::where('category', '<>', 'LIABILITY')->get();
		$toAccounts = Account::where('category', 'LIABILITY')->get();
		$amount = ClaimReceiptItem::getTotals(ClaimReceipt::getId($id))->grand;

		return View::make('expense_claims.payClaim', compact('fromAccounts', 'toAccounts', 'amount', 'id'));
	}


	/**
	 * PAY EXPENSE CLAIM
	 */
	public function payClaim(){
		$input = Input::all();
		$fromAmount = Account::where('id', Input::get('from_account'))->pluck('balance');
		if($fromAmount < Input::get('claim_amount')){
			return Redirect::action('ExpenseClaimController@payClaimView', array(Input::get('claim_id')))
							->with('error', 'Insufficient funds in the from account! Please select another Account.');
		}

		// Save input in an Array
		$data = array(
			'date' => date('Y-m-d'),
			'initiated_by' => Confide::user()->username,
			'amount' => array_get($input, 'claim_amount'),
			'debit_account' => array_get($input, 'to_account'),
			'credit_account' => array_get($input, 'from_account'),
			'description' => 'Payed an outstanding Expense Claim'
		);

		// Affect Account balances
		DB::table('accounts')
				->where('id', $data['credit_account'])
				->decrement('accounts.balance', $data['amount']);

		DB::table('accounts')
				->where('id', $data['debit_account'])
				->decrement('accounts.balance', $data['amount']);

		// Update Expense Claim & Receipt Statuses
		ClaimReceipt::where('claim_id', Input::get('claim_id'))->update(array('status'=>'Payed'));
		ExpenseClaim::where('id', Input::get('claim_id'))->update(array('status'=>'Payed'));

		// Create journal Entries an Transaction details
		$journal = new Journal;
		$acTransaction = new AccountTransaction;

		$journal->journal_entry($data);
		$acTransaction->createTransaction($data);

		$credit = Account::find($data['credit_account']);
		$debit = Account::find($data['debit_account']);


		Audit::logaudit('Expense Claims', 'paid expense claim', 'paid expense claim for claimer '.Confide::user()->username.' amount '.array_get($input, 'claim_amount').' credited to account '.$credit->name.' and debited to account '.$debit->name.' in the system');

		return Redirect::action('ExpenseClaimController@index')->with('success', 'Expense Claim successfully paid');
	}

}
