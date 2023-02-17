import React, { useEffect, useState } from "react";
import * as Yup from "yup";
import Login from "../../screens/Login/Login";
import api from "../../services/api";
import { idSchool, login } from "../../services/auth";
import { useHistory } from "react-router-dom";
import { QueryCache } from "react-query";

const SignIn = () => {
  const [isValid, setValid] = useState(true);
  let history = useHistory();

  // useEffect(() => {
  //   QueryCache.clear()
  // }, []);

  const onSubmit = values => {
    api.post("auth/login", values).then(function (response) {
      if (response && !("error" in response.data)) {
        console.log(response)
        if (response.data.user.schools[0]) {
          idSchool(response.data.user.schools[0].inep_id)
        }
        login(response.data.access_token);
        history.push("/");
      } else {
        setValid(false);
      }
    });
  };

  const validationSchema = Yup.object().shape({
    username: Yup.string().required("Usuário é obrigatório!"),
    password: Yup.string()
      .min(6, "Senha deve ter no mínimo 6 caracteres")
      .required("Senha é obrigatória!")
  });

  let initialValues = {
    email: "",
    password: ""
  };

  return (
    <Login
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={onSubmit}
      isValid={isValid}
    />
  );
};

export default SignIn;
