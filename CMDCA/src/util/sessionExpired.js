import Swal from 'sweetalert2'
import withReactContent from 'sweetalert2-react-content'

const alert= withReactContent(Swal); 
export default function sessionExpired(){
    alert.fire({
        icon: 'error',
        title: 'Sua sessão expirou!',
        text: 'Você será redirecionado para a página de login!',
        onClose: ()=>{
            localStorage.clear();
            window.history.replaceState(null,"login","/");
            window.history.go();
        }
    });
}