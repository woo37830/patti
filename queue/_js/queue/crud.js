// Provide CRUD functions
// J Wooten, 20160214
		var url;
		var row;
		function newEntry(user,org){
			$('#dlg').dialog('open').dialog('setTitle','New Entry');
			$('#fm').form('clear');
			url = './save_entry.php?uName='+user+'&org='+org;
		}
		function editEntry( id, author, title, genre, notes){
			if (author){
			//alert("Values are: "+author+", "+title);
				$('#dlg').dialog('open').dialog('setTitle','Edit Entry');
				$('#fm').form('load',{
				    entry: id,
				    application: author,
				    process: title,
				    activity: genre,
				    msg: notes
				});
				row = id;
				url = './update_entry.php?id='+id;
			}
		}
		function saveEntry(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
				    //alert('Into success, result ='+result);
					//var result = eval('('+result+')');
					//if (result.errorMsg){
					//	    $.messager.show({
					//		title: 'Error',
					//		msg: result.errorMsg
					//	});
					//} else {
                            $('#dlg').dialog('close');		// close the dialog
                            oTable.ajax.reload(null, false);
                       // }
				}
			});
		}
		function destroyEntry(){
                var id = row;
			if (id){
				$.messager.confirm('Confirm','Are you sure you want to destroy entry '+id+'?',function(r){
					if (r){
						$.post('./destroy_entry.php',{id:id},function(result){
							if (result.success){
						            $('#dlg').dialog('close');		// close the dialog
                                    oTable.ajax.reload(null, false);
                                } else {
								    $.messager.show({	// show error message
									title: 'Error',
									msg: result.errorMsg
								});
							}
						},'json');
					}
				});
			}
		}
