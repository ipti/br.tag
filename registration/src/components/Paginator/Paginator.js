import React, { useState } from "react";
import { Link } from "react-router-dom";
import { makeStyles } from "@material-ui/core/styles";
import styles from "./styles";
const useStyles = makeStyles(styles);

const Pagination = props => {
  const { handlePage } = props;
  const [perPage] = useState(props.pagination.perPage);
  const [totalPages] = useState(props.pagination.totalPages);
  const [totalItens] = useState(props.pagination.totalItens);
  const [startPage, setPageStart] = useState(1);
  const [active, setActive] = useState(1);

  const classes = useStyles();

  const nextPage = () => {
    if (startPage < totalPages - perPage + 1) {
      setPageStart(startPage + 1);
    }
  };

  const prevPage = () => {
    if (startPage > 1) {
      setPageStart(startPage - 1);
    }
  };

  const activePage = page => {
    handlePage(page);
    setActive(page);
  };

  const paginationItens = () => {
    const pageNumbers = [];
    let end = startPage + perPage - 1;

    for (let i = startPage; i <= end; i++) {
      pageNumbers.push(i);
    }

    if (totalItens > perPage) {
      return (
        <nav className={classes.root}>
          <span onClick={prevPage} className={classes.arrows}>
            {"<"}
          </span>
          <ul className={classes.paginate}>
            {pageNumbers.map(page => (
              <li key={page}>
                <Link
                  className={active === page ? classes.activePage : ""}
                  onClick={() => activePage(page)}
                  to="#"
                >
                  {page}
                </Link>
              </li>
            ))}
          </ul>
          <span
            onClick={nextPage}
            className={`${classes.arrows} ${classes.arrowsRight}`}
          >
            {">"}
          </span>
        </nav>
      );
    }

    return "";
  };

  return paginationItens();
};

export default Pagination;
