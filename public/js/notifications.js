type = ['primary', 'info', 'success', 'warning', 'danger'];
icons = {bell:'nc-icon nc-bell-55',cloud:'fa fa-cloud',error:'fa fa-exclamation-triangle', check:'fa fa-check'};

notifyAction = {

    // action -> pass the color ID.
    showNotification: function(action, message, iconview) {
    // color = Math.floor((Math.random() * 4) + 1); 
        $.notify({
        destroy: false,
        icon: icons[iconview],
        message: message

        }, {
        type: type[action],
        timer: 1000,
        placement: {
            from: 'top',
            align: 'right'
        }
        });
    },
}