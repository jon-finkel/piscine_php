var list = [];
var id_number = 0;
var from_cookies = 0;

function add(entry) {
    var task;
    if (!from_cookies) {
        task = prompt("What would you like to add to the list?");
    }
    else {
        task = entry;
    }
    if (task.length !== 0) {
        var ft_list = document.getElementById('ft_list');
        var elem = document.createElement('div');
        elem.setAttribute('id', id_number);
        elem.setAttribute('onclick', "del(this)");
        var text = document.createTextNode(task);
        list[id_number] = task;
        ++id_number;
        elem.appendChild(text);
        ft_list.insertBefore(elem, ft_list.childNodes[0]);
    }
    if (!from_cookies) {
        update_cookies();
    }
}

function del(task) {
    if (confirm("Do you really want to delete this item?") === true) {
        list.splice(task.getAttribute('id'), 1);
        task.parentNode.removeChild(task);
        update_cookies();
    }
}

function update_cookies() {
    var string = JSON.stringify(list);
    console.log(list);
    document.cookie = "todo="+string;
}

window.onload = function() {
    if (document.cookie) {
        var todo = JSON.parse(document.cookie.split('=')[1]);
        from_cookies = 1;
        for (var k in todo) {
            add(todo[k]);
        }
        from_cookies = 0;
    }
};
