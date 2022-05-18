(function () {

    const select = (selector, from = document, all = false) => {
        if (all) {
            return from.querySelectorAll(selector)
        }
        return from.querySelector(selector)
    }

    select('button[data-action=open]', document, true).forEach(btn => {
        btn.addEventListener('click', async e => {
            const id = btn.dataset.id
            fetch('/requests.php', {
                method: 'POST',
                body: JSON.stringify({id: id, action: 'get_description'})
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    const { description } = data.data

                    select('.item_description').classList.remove('d-none')
                    select('.item_description .card-body').innerHTML = description
                }
            })

        })
    })

    select('button[data-action=edit]', document, true).forEach(btn => {
        btn.addEventListener('click', async e => {
            const id = btn.dataset.id
            fetch('/requests.php', {
                method: 'POST',
                body: JSON.stringify({id: id, action: 'edit_item'})
            }).then(response => response.json())
            .then(data => {
                const form = select('#edit_form')

                if (data.success) {
                    const { item, parents } = data.data

                    select('.form_container').classList.remove('d-none')
                    select('#id', form).value = item.id
                    select('button[data-action=delete]', form).classList.remove('d-none')
                    select('button[data-action=delete]', form).dataset.id = item.id
                    select('#name', form).value = item.title
                    select('#description', form).value = item.description
                    select('#parents', form).innerHTML = ''

                    parents.forEach((value, index) => {
                        let selected = ''
                        if (value.id === item.parent_id) {
                            selected = 'selected'
                        }
                        form.querySelector('#parents').innerHTML += `<option value="${value.id}" ${selected}>${value.title}</option>`
                    })

                }
            })

        })
    })

    select('button[data-action=add]', document, true).forEach(btn => {
        btn.addEventListener('click', async e => {
            fetch('/requests.php', {
                method: 'POST',
                body: JSON.stringify({action: 'add_item'})
            }).then(response => response.json())
            .then(data => {
                const form = select('#edit_form')

                if (data.success) {
                    const { parents } = data.data

                    select('.form_container').classList.remove('d-none')
                    select('#id', form).value = 0
                    select('button[data-action=delete]', form).classList.add('d-none')
                    select('#name', form).value = ''
                    select('#description', form).value = ''
                    select('#parents', form).innerHTML = ''

                    parents.forEach((value, index) => {
                        form.querySelector('#parents').innerHTML += `<option value="${value.id}">${value.title}</option>`
                    })

                }
            })

        })
    })

    select('#edit_form').addEventListener('submit', e => {
        e.preventDefault()
        const data = {}
        const formData = new FormData(e.target)
        formData.forEach((value, key) => data[key] = value)
        data.action = 'save_item'

        select('#submit').disabled = true
        fetch('/requests.php', {
            method: 'POST',
            body: JSON.stringify(data)
        }).then(response => response.json())
        .then(data => {
            select('#submit').disabled = false
            if (data.success) {
                location.reload()
            }
        })

        return false
    })

    select('button[data-action=delete]').addEventListener('click', e => {
        const id = e.target.dataset.id
        const data = {action: 'delete_item', id: id}
        fetch('/requests.php', {
            method: 'POST',
            body: JSON.stringify(data)
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload()
            }
        })
    })

})()