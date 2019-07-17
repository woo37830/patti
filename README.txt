There are several php files and some test html files in the test directory.

The test-rest-form.html brings up a simple form to enter a name, phone number, and message.  It then sends that information to the rest-form.php which packages it and sends it to TextMagic.  This has been validated and the messages are being received at TextMagic.  I have received textmessages from this service.  It correctly handles the number in the form (813) 555-1212 form or others ways with (, -, and non numeric characters and prepends the 1 in front if only 10 numbers are there.

The text-reply.php file currently receives the response from text magic and should write out the information to a text-reply.txt file for inspection.  THIS NOW WORKS 20190716
After this is working, I will use the master api key from AllClients to
update the client using his phone number and put what the response was.

The test_consume_json.html form inputs the data into a small form, and sends it as json data to the file
consume_json.php where at this time it is output back to the browser as a string of json text.
This will be changed to send any needed json data back to AllClients or the Credit card handler, etc. as necessary.

I suppose that the rest-form.php should also update the client using the clientid provided to note that the text was sent.
