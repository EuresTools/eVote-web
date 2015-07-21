// override alert function to use bootbox
alert = function (message) {
    bootbox.alert(message, function() {
      
    });
}

/*
// override normal confirm and use bootbox for confirmation
yii.confirm = function (message, ok, cancel) {
  bootbox.confirm(message, function (confirmed) {
    if (confirmed) {
      !ok || ok();
    } else {
      !cancel || cancel();
    }
  });
}
*/


yii.confirm = function (message, ok, cancel) {
    bootbox.confirm(
        {
            message: message,
            buttons: {
                confirm: {
                    label: "OK"
                },
                cancel: {
                    label: "Cancel"
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
}


// old code 
// yii.allowAction = function ($e) {
//   var message = $e.data('confirm');
//   return message === undefined || yii.confirm(message, $e);
// };

// yii.confirm = function (message, $e) {
//   bootbox.confirm(message, function (confirmed) {
//     if (confirmed) {
//       yii.handleAction($e);
//     }
//   });
  
//   // confirm will always return false on the first call
//   // to cancel click handler
//   return false;
// }

