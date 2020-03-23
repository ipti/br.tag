import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'

const alert= withReactContent(Swal); 
export default function swalert(icon,title,text){
    alert.fire({
        icon: icon,
        title: title,
        text: text,
    });
}