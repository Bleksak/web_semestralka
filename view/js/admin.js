const select = document.getElementsByClassName("role_change")

let map = []

document.body.onload = () => {
    for(var i = 0; i < select.length; ++i) {
        map.push(select[i].value)
        select[i].addEventListener("change", onChange);
    }
}

const findIndex = (element) => {
    for(var i = 0; i < select.length; ++i) {
        if(select[i] == element) return i;
    }

    return -1;
}

const onChange = (ev) => {
    ev.preventDefault()

    const action = ev.target.form.action
    const role = ev.target.value
    const url = action + role

    if(window.confirm("Opravdu chcete uživateli změnit roli?")) {
        window.location.replace(url)
    } else {
        const oldValue = map[findIndex(ev.target)]
        ev.target.value = oldValue
    }
}