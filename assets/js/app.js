import '../styles/app.css';
import {Routing} from './routes.js';

// Demo purposes only
$(".hover").mouseleave(
  function () {
    $(this).removeClass("hover");
  }
);

// Collection type on the project form
const newItem = (e) => {
  const collectionHolder = document.querySelector(e.currentTarget.dataset.collection);

  const item = document.createElement("div");
  item.classList.add("col-4");
  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    );

  item.querySelector(".btn-remove").addEventListener("click", () => item.remove());

  collectionHolder.appendChild(item);
  collectionHolder.dataset.index++;
};

document
  .querySelectorAll('.btn-remove')
  .forEach(btn => btn.addEventListener("click", (e) => e.currentTarget.closest(".col-4").remove()));

document
  .querySelectorAll('.btn-new')
  .forEach(btn => btn.addEventListener("click", newItem));

// tinyMCE
tinymce.init({
  selector: '#project_description',
  plugins: [
    'link',
    'textcolor',
    'lists',
    'autolink',
    'anchor',
    'lineheight'
  ],
  toolbar: [
    'undo redo | bold italic underline | fontsizeselect lineheight lineheightselect'
  ],
  fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 36pt 48pt',
  lineheight_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 36pt",
});

// Blueimp Gallery
document.getElementById('links').onclick = function (event) {
  event = event || window.event
  const target = event.target || event.srcElement;
  const link = target.src ? target.parentNode : target;
  const options = {index: link, event: event};
  const links = this.getElementsByTagName('a');
  blueimp.Gallery(links, options)
};