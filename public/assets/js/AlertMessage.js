function pesanAlert(obj, pos = "center") {
    let color = "";
    let msg = "";
    switch (parseInt(obj.code)) {
        case 1:
            color = "success";
            msg = obj.message;
            break;
        case 0:
            color = "warning";
            msg = obj.message;
            break;
        default:
            color = "error";
            msg = "Internal Server Error!";
            break;
    }
    notif({
        msg: '<b style="color: white;">' + msg + '</b>',
        type: color,
        position: pos
    });
}