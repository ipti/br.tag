import React, { useState } from "react";

import RegistrationContext from "./context";

// Redux

// Components
import Alert from "../../components/Alert/CustomizedSnackbars";
import Loading from "../../components/Loading/CircularLoadingRegistration";
import Wait from "../../screens/Registration/Wait";
import Wizard from "../../screens/Registration/Wizard";

// Material UI
import Grid from "@material-ui/core/Grid";
import { Controller } from "../../controller/registration";
import { useFetchRequestSchoolList } from "../../query/registration";

const Home = props => {
  const [loadDataStudent, setLoadDataStudent] = useState(false);
  const [load, setLoad] = useState(true)
  const [idSchool, setIdSchool] = useState('');
  const [year, setYear] = useState('');
  const [idStage, setIdStage] = useState('');
  const [idStagevsmodality, setIdStagevsmodality] = useState('');
  const [idEvent, setIdEvent] = useState('');
  const [open, setOpen] = useState(false);
  const [number, setNumber] = useState("");
  const [step, setStep] = useState(0);
  const [dataValues, setDataValues] = useState({});
  const { requestSaveRegistrationMutation } = Controller()
  const [isActive, setIsActive] = useState(true);

  // console.log(isActive)
  // useEffect(() => {
  //   setOpen(false);

  //   if (loadDataSchool) {
  //     props.dispatch({ type: "FETCH_SCHOOLS_LIST" });
  //     setLoadDataSchool(false);
  //   }

  //   // if (loadPeriod) {
  //   //   props.dispatch({ type: "FETCH_PERIOD" });
  //   //   setLoadPeriod(false);
  //   // }


  //   if (step === 2 && props?.student?.status === "1") {
  //     setStep(3);
  //   }

  //   if (props?.registration?.status === "1" && !props.loading) {
  //     setStep(6);
  //   }

  //   if (props?.student?.status === "1" && !props.loading) {
  //     setStep(5);
  //   }

  //   if (props.openAlert) {
  //     setTimeout(function () {
  //       props.dispatch({ type: "CLOSE_ALERT_REGISTRATION" });
  //     }, 3000);
  //   }
  // }, [loadDataSchool, loadDataStudent, loadPeriod, number, open, props, step]);


  const { data } = useFetchRequestSchoolList();

  const onSubmit = () => {


    if (dataValues?.birthday) {
      dataValues.birthday = dataValues.birthday
        .split("/")
        .reverse()
        .join("-");
    }

    // console.log(dataValues)
    const parseBool = value =>
      ['true', 'false'].includes(value) ? value === true : null
    if (load && dataValues.cep) {
      requestSaveRegistrationMutation.mutate(
        {
          ...dataValues, sex: parseInt(dataValues.sex),
          zone: parseInt(dataValues.zone),
          deficiency: parseBool(dataValues.deficiency),
          cpf: dataValues.cpf.replace(/\D/g, ''),
          responsable_cpf: dataValues.responsable_cpf.replace(/\D/g, ''),
          responsable_telephone: dataValues.responsable_telephone.replace(/\D/g, ''),
          father_name: dataValues.father_name === "" ? null : dataValues.father_name,
          mother_name: dataValues.mother_name === "" ? null : dataValues.mother_name,
          event_pre_registration: parseInt(dataValues.event_pre_registration),
          stages_vacancy_pre_registration: parseInt(dataValues.stages_vacancy_pre_registration)
        }
      )
      setLoad(false)
    }

  };

  const next = (step, values) => {

    console.log(values)

    let data = Object.assign(dataValues, values);

    console.log(data)
    setDataValues(data);
    setStep(step)

    if (step === 7 && dataValues.cep) {
      onSubmit();
    }

    // if (step === 3 && values && values.numRegistration !== "") {
    //   getDataStudent(values.numRegistration);
    // } else {
    //   if (step === 7) {
    //     onSubmit();
    //     setStep(step)
    //   } else {
    //     setStep(step);
    //   }
    // }
  };

  const getDataStudent = number => {
    setNumber(number);
    setLoadDataStudent(true);
  };

  const handleClose = () => {
    setOpen(false);
  };

  const alert = () => {
    return (
      <Alert
        open={props.openAlert}
        handleClose={handleClose}
        status="0"
        message="Matrícula não encontrada!."
      />
    );
  };

  const handleStudent = isStudent => {
    if (!isStudent) {
      next(4);
    } else {
      next(3);
    }
  };

  const wizard = () => {
    // const isActive =
    //   props.period?.data?.internal === true ||
    //   props.period?.data?.newStudent === true ||
    //   props?.student?.status ||
    //   step === 6 ||
    //   props.loading;
    return (
      <RegistrationContext.Provider
        value={{ idEvent, setIdEvent, idSchool, setIdSchool, idStage, setIdStage, idStagevsmodality, setIdStagevsmodality, year, setYear }}
      >
        <Grid
          container
          justifyContent="center"
          alignItems="center"
          style={{ minWidth: "100%" }}
        >
          <Grid item lg={4} md={5} xs={10}>
            {isActive ? (
              <Wizard
                schools={data}
                next={next}
                step={step}
                handleStudent={handleStudent}
                loadingButtom={props.loading}
                setIsActive={setIsActive}
                handleSubmit={onSubmit}
              />
            ) : (
              <Wait />
            )}
          </Grid>
        </Grid>
      </RegistrationContext.Provider>

    );
  };

  return (
    <>
      {props.loading && step === 0 ? (
        <Loading />
      ) : (
        <>
          {wizard()}
          {alert()}
        </>
      )}
    </>
  );
};

// const mapStateToProps = state => {

//   return {
//     address: state.viaCep.addresses,
//     student: state.registration.student,
//     registration: state.registration.registration,
//     period: state.registration.period,
//     schoolList: state.registration.schoolList,
//     loading: state.registration.loading,
//     openAlert: state.registration.openAlert
//   };
// };

export default Home;
