document.addEventListener('DOMContentLoaded', ()=>{
    const likeButtons = document.querySelectorAll('.like');
    likeButtons.forEach((button)=>{
        button.addEventListener('click', like)
    })
})

function like(e) {
    e.preventDefault()
    fetch(this.href)
        .then((res)=>res.json())
        .then((data)=>{
            console.log(data)
            this.querySelector('.count').innerHTML = data.count
            if(data.liked){
                this.classList.add('btn-success')
                this.classList.remove('btn-secondary')
            } else {
                this.classList.add('btn-secondary')
                this.classList.remove('btn-success')
            }
        })
}