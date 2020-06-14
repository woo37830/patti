I have created an email-pipe on mailnuggets.com with email address:
  mail@woo37830.mailnuggets.com

When this is used as the bcc address, then the email goes to the addressee, and a copy
  goes to the url specified in the email-pipe rule on mailnuggers.com under
  account woo37830.  Currently the URL points to http://jwooten37830.com/patti/email_pipe/postTest.php

  This piece of code parses out the data and logs it to a timed log file and responds
  to mailnuggets.com where it is logged under the woo37830 account.

Now that we know how to get the from, to, subject, and the original email body,
it is only necessary to:

  1.  Use the from address as the email of the account holder
  2.  Look in the users data table and get the EngagemorecrmID for that email.
  3.  Look to see if the to address is in the users data table.
    1.  If it is, then note that and get the engagemorecrmID for that address.
    2.  If not, note that also.
  4.  Use the https://secure.engagemorecrm.com/api/2/AddContactNote.aspx
  5.  Provide:
    1.  accountid from allClients
    2.  apikey    from allClients
    3.  teammemberid from allClients, 0 is account owner
    4.  identifymethod - 0 is contactid, 1 is by email, 2 is by email or other email
    5.  identifyvalue - integer if contactid otherwise email address
    6.  note - the note to be attached, text only, '\n' for new line.

  Success message:

  <?xml version="1.0"?>
<results>
    <message>Success</message>
    <noteid>15631</noteid>
</results>

Failure message:

<?xml version="1.0"?>
<results>
    <error>Authentication failed</error>
</results>

or,

<?xml version="1.0"?>
<results>
    <error>Add Note failed: Contact not found</error>
</results>
