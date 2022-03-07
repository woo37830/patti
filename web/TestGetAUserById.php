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
   $k = 0;
   if( $id = 2607 ) {
     echo "\naccountid ".$accounts->accounts->account->accountid."\n";
   }
   foreach( $accounts->accounts->account as $account ) {
     if( $account->accountid == $id ) {
       if( $id = 2607  ) {
         echo "Account Email : $account->email";
       }
       return $id;
     }
     $k++;
   }
   return -1;
 }

 function getAccountByEmail( $email, $accounts ) {
//   echo "Looking for $email";
   foreach( $accounts->accounts->account as $account ) {
//     echo "Checking: $account->email";
     if( strcmp($account->email,$email) == 0 ) {
       return $account;
     }
   }
   return -1;
 }

 function getAccountEmailById( $id, $accounts ) {
   foreach( $accounts->accounts->account as $account ) {
     if( $account->accountid == $id ) {
       return $account->email;
     }
   }
   return "";
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
 echo "\nUser: ".$aUser."\n";
 $anAccount = getAccountById( 2607, $accounts);
 echo "\nAccount: ".$anAccount."\n";

 $byEmail = getAccountByEmail( 'jwooten37830@icloud.com', $accounts );
 echo "\nEmail: ".$byEmail->email."\n";

 $byId = getAccountEmailById( $anAccount, $accounts);
 echo "\nEmailById: ".$byId."\n";
 ?>
