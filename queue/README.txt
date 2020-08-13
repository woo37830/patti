The files in tests directory exhibit how to parse a json file for users, apps, and processes.

It is intended that after the users role is obtained, then
the list of apps visible to that role and the processes that that role can init
be presented in a set of drop-down menus.  The link in the process would contain
the file name of the state machines xml file.  The  link in the app file might contain
the description of that app?

Now, the app has been refactored so that after login, the home.page replaces the
Welcome message.  It should actually come from organizations/organization/home.page
after reading in the org from the users credentials.

When a row in the table is clicked in the home page, then it fires the deQueue function
which reads the queue entry data and loads the queue_entry.page to replace the home.page.

One problem is that if you click reload, the text on the page becomes "Welcome..."

Possibly, I could store the current page in the cookie, and if it is there, then load
that page?
A comment for testing git-sync
