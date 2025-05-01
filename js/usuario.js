const navMenu = document.getElementById('nav-menu');
const navToggle = document.getElementById('menu');
const navClose = document.getElementById('nav-close')

/* Menu show */
if(navToggle){
   navToggle.addEventListener('click', () =>{
      navMenu.style.right = '0'
      navMenu.style.transition = '0.5s'
   })
}
if(navClose){
   navClose.addEventListener('click', () =>{
      navMenu.style.right = '-100%'
      navMenu.style.transition = '0.5s'
   })

}
