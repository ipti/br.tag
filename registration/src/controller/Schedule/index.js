import { useMutation } from "react-query";
import { useHistory } from "react-router";
import { requestSaveEventPre } from "../../query/Schedule";

export const Controller = () => {
    const history = useHistory()
    const requestSaveEventPreMutation = useMutation(
        (data) => requestSaveEventPre(data),
        {
          onError: (error) => {
            console.log(error.response.data.message);
          },
          onSuccess: (data) => {
            history.push('/')
          },
        }
      );
    return {requestSaveEventPreMutation}
} 