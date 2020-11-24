<?php

/*
 * Find and return the json list of accounts that do not have entries ( e.g. by id, or email)
 * in the users table which is built from Thrivecart adds and cancellations.
 *
 */

 require './get_all_accounts.php';
 require './get_users.php';

 function getAUserById( $id, $users ) {
   foreach($users as $user) {
     if( $user['engagemoreid'] == $id ) {
       return $id;
     }
   }
   return -1;
 }

 function getAccountById( $id, $accounts ) {
   foreach( $accounts as $account ) {
     if( $account->accountid == $id ) {
       return $account;
     }
   }
   return -1;
 }
 function getAccountByEmail( $email, $accounts ) {
   foreach( $accounts as $account ) {
     if( $account->email == $email ) {
       return $account;
     }
   }
   return -1;
 }

 $accounts = getAccounts(); // The collection of all accounts in CRM (accounts.account.[id,email...])

 $users = getUsers(); // The collection of all users (data[i].[id,email,...])

 function testIt() {

   $isolated_accounts = array();
   foreach($accounts as $account)
   {
     if(  getAUserById( $account->accountid, $users ) == -1 )
     {
   			array_push($isolated_accounts,$account);
     }
   }
   return $isolated_accounts;
 }

 $aUser = getAUserById( 2607, $users);
 echo "\n".$aUser."\n";
 ?>
