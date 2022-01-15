<?php

/*
 * Find and return the json list of accounts that do not have entries ( e.g. by id, or email)
 * in the users table which is built from Thrivecart adds and cancellations.
 *
 */

 require './get_all_accounts.php';
 require './get_users.php';

 function getAUser( $email, $users ) {
   foreach($users as $user) {
     if( $user['email'] == $email ) {
       return $user;
     }
   }
   return -1;
 }

 function getAUserById( $id, $users ) {
   foreach($users as $user) {
     if( $user['engagemoreid'] == $id ) {
       return $id;
     }
   }
   return -1;
 }

 function getAccountEmailById( $id, $accounts ) {
   foreach( $accounts->accounts->account as $account ) {
     if( $account->accountid == $id ) {
       echo "\naccount->email = $account->email\n";
       return $account->email;
     }
   }
   return array();
 }

 function getAccountById( $id, $accounts ) {
   foreach( $accounts->accounts->account as $account ) {
     if( $account->accountid == $id ) {
       return $id;
     }
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

 $accounts = getAccounts(); // The collection of all accounts in CRM (accounts.account.[id,email...])

 $users = getUsers(); // The collection of all users (data[i].[id,email,...])
 $results = array();
 $isolated_users = array();
 foreach($users as $user)
 {
   $db_email = $user['email'];
   $acct_id = getAccountById( $user['engagemoreid'], $accounts );
   if( $acct_id  != -1 )
   { // The db user was located in AllClients by their id
      $ac = getAccountByEmail( $db_email, $accounts);
      if( $ac == -1 )
      {
        echo "\nAccountByEmail $db_email, ac: $ac\n";
        echo $db_email;
        echo $acct_id;
        {
            $str = getAccountEmailById($acct_id,$accounts);
            $user['ac_email'] = "";
  					$user['db_email'] = $db_email;
     			array_push($isolated_users,$user);
          break;
        }
      }
   }
 }
 $result["data"] = $isolated_users;
 echo json_encode($result);

 ?>
