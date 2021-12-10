const search_submit = (ev) => {
    ev.preventDefault()

    const form = ev.target
    const value = document.querySelector("#searchbox").value
    const action = form.getAttribute("action")

    if (value !== "") {
        const url = action + "/" + value
        window.location.replace(url)
    }
}

document.querySelector("#searchform").addEventListener('submit', search_submit);