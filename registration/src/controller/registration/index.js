import { useMutation } from "react-query";
import { useHistory } from "react-router";
import Swal from "sweetalert2";
import { requestSaveRegistration } from "../../query/registration";

export const Controller = () => {
    const history = useHistory()
    const requestSaveRegistrationMutation = useMutation(
        (data) => requestSaveRegistration(data),
        {
          onError: (error) => {
            Swal.fire(error.response.data.message);
          },
          onSuccess: (data) => {
            history.push('/')
          },
        }
      );
    return {requestSaveRegistrationMutation}
} 