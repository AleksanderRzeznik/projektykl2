const btn_dodaj = document.querySelector('#btn_dodaj')

btn_dodaj.addEventListener('click', (e) => {
    e.preventDefault()
    const text = document.querySelector('input[type="text"]').value
    const date = document.querySelector('input[type="date"]').value
    

    if (text === '' || date === '') {
        alert("formulaż nie może być pusty")
    } else {
        const row = `
        <tr>
        <td>${text}</td>
        <td>${date}</td>
        <td class = 'btn_usun'> <button>USUŃ</button> </td>
        </tr>
        `
        localStorage.setItem('tabela', 'row')
        const table = document.querySelector('table')
        table.innerHTML += row
        
        document.querySelector('input[type="text"]').value = ``
        document.querySelector('input[type="date"]').value = ``
        
        const btns_usun = document.querySelectorAll(".btn_usun")
        for(let btn_usun of btns_usun){
            btn_usun.addEventListener('click', (e) => {
                btn_usun.parentElement.parentElement.remove()
                localStorage.removeItem('table')
                localStorage.setItem('table', table.innerHTML)
            })
        }
        
    }
    console.log(localStorage.getItem('table'))
})