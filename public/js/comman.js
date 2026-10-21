/*
 * Laravel csrf token generated.
 * Data fetch and Send
 * @param {type} link_url // dynamic URL 
 * @param {type} data / initialization post data in param 
 * @param {type} types // put the method type get and post
 * @param {type} callBack // return  call back function
 * @param {type} datatype
 * @returns {undefined}
 */
function fetch_action_csrftoken(link_url, data, types, callBack,datatype, csrf_token) 
{
    if (datatype === undefined) {
        datatype = 'json';
    }
    if (types === undefined) {
        types = 'POST';
    }
    if(csrf_token === undefined) {
        csrf_token = $('meta[name="csrf-token"]').attr('content');
    }
    
    $.ajax({ // jQuery Ajax
        type: types,
        url: link_url, // URL to the PHP file which will insert new value in the database
        data: data, // We send the data string
        dataType: datatype, // Json format
        timeout: 150000,
        async: true,
        headers: {'X-CSRF-TOKEN': csrf_token},
        success: function(data) {
            return callBack(data); 				 
        },
        error: function(x, t, m) {
            if(t==="timeout") {
                return;
            } else {
                console.log(t);
            }
            return callBack(-1); 
        }
    });	
}
/*
 * Data fetch and Send
 * @param {link_url} link_url // dynamic URL 
 * @param {data} data / initialization post data in param 
 * @param {types} types // put the method type get and post
 * @param {callBack} callBack // return  call back function
 * @param {datatype} datatype
 * @returns {undefined}
 */
function fetch_action(link_url, data, types, callBack,datatype) 
{
    if (datatype === undefined) {
        datatype = 'json';
    }
    if (types === undefined) {
        types = 'POST';
    }
	
    $.ajax({ // jQuery Ajax
        type: types,
        url: link_url, // URL to the PHP file which will insert new value in the database
        data: data, // We send the data string
        dataType: datatype, // Json format
        timeout: 150000,
        async: true,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(data) {
            return callBack(data); 				 
        },
        error: function(x, t, m) {
            if(t==="timeout") {
                    return;
            } else {
                console.log(t);
            }
            return callBack(-1); 
        }
    });	
}
/*
 * File upload and video with ajax
 * @param {link_url} link_url // dynamic URL 
 * @param {data} data / initialization post data in param 
 * @param {types} types // put the method type get and post
 * @param {callBack} callBack // return  call back function
 * @param {datatype} datatype
 * @returns {undefined}
 */
function upload_action(link_url, data, types, callBack,datatype) 
{
    if (datatype === undefined) {
        datatype = 'json';
    }
    if (types === undefined) {
        types = 'POST';
    }
	
    $.ajax({ // jQuery Ajax
        type: types,
        url: link_url, // URL to the PHP file which will insert new value in the database
        data: data, // We send the data string
        dataType: datatype, // Json format
        timeout: 150000,
        async: true,
        cache: false,
        contentType: false,        
        enctype: 'multipart/form-data',
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(data) {
            return callBack(data); 				 
        },
        error: function(x, t, m) {
            if(t==="timeout") {
                    return;
            } else {
                console.log(t);
            }
            return callBack(-1); 
        }
    });	
}

/*
 * 
 * @param {action} action // pass the param in action success, error, info, question
 * @param {msg} msg / message initialization in param 
 * @param {formatmsg} color // message color
 * @param {timeout} timeout // time set
 */
function msg_alert(action, msg, formatmsg, timeout) {
    if(timeout === undefined) {
        timeout = 3000;
    }
    if(action === undefined) {
        action = '';
    }
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: timeout
    });
    if(action) {
      Toast.fire({
        icon: action,
        title: msg
      });
    } else {
      switch(formatmsg) {
        case "success":
          toastr.success(msg);
        break;
        case "info":
          toastr.info(msg);
        break;
        case "error":
          toastr.error(msg);
        break;
        case "warning":
          toastr.warning(msg);
        break;
        default:
          break;
        // code block
      }
    }
  }

 

/*
 * Live Search through ajax
 * @param {link_url} link_url // send url
 * @param {data} data // pass post value in param
 * @param {callBack} callBack // call back function
 * @param {loaderId} datatype // datatype
 * @returns {undefined}
 */
function liveSearch(link_url, data, types, callBack, loaderId) // send record 
{
    if (types === undefined) {
      types = 'POST';
    }
	
    $.ajax({
		type: types,
		url: link_url,
		data: data,
        dataType:"json", // Json format
        timeout: 150000,
        async: true,        
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		success: function(data) {
            return callBack(data);
		},
        error: function(x, t, m) {
            if(t==="timeout") {
                return;
            } else {
                console.log(t);
            }
            return callBack(-1); 
        }
	});
}

/*
 * Get the responce from controller
 * @param {link_url} link_url // url link
 * @param {data} data // inisiligetion post data
 * @param {contentid} contentid // div id
 * @param {type} type // this method post and get or put
 * @param {refresh} refresh // optional param and refresh dropdown 
 * @returns {undefined}
 */
function fetch_data(link_url, data, contentid, type, refresh) // fetch record
{
    if (refresh === undefined) {
        refresh = 0;
    }
    if(type === undefined) {
        type = "POST";
    }
    $.ajax({ // jQuery Ajax
        type: type,
        url: link_url, // URL to the PHP file which will insert new value in the database
        data: data, // We send the data string
        dataType: 'json', // Json format
        timeout: 150000,
        success: function(data) {
            $(contentid).html(data.content); // We update the articleArea DIV with the article list
            if(refresh==1)
                $(contentid).trigger('contentChanged');
        },
        error: function(x, t, m) {
            if(t==="timeout") {
                return;
            } else {
                console.log(t);
            }
            return callBack(-1); 
        }
    });
}

/*
 * 
 * Data fetch and Send
 * @param {type} link_url // dynamic URL 
 * @param {type} data / initialization post data in param 
 * @param {type} types // put the method type get and post
 * @param {type} callBack // return  call back function
 * @param {type} datatype
 * @returns {undefined}
 */
function panelPage(link_url, data, types, callBack,datatype, csrf_token) 
{
    alert(link_url);
}