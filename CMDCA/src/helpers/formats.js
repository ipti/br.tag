export const formatPhone = v => {
    if (!v) return v;
  
    v = v.toString().replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
    v = v.replace(/(\d)(\d{4})$/, "$1-$2");
    return v;
  };
  
  export const formatTime = v => {
    if (!v) return v;
  
    v = v.toString().replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "$1:$2");
    return v;
  };

  export const formatDate = v => {
    if (!v) return v;
  
    v = v.toString().replace(/\D/g, "");
    v = v.replace(/^(\d{2})(\d)/g, "$1/$2");
    v = v.replace(/(\d)(\d{4})$/, "$1/$2");
    return v;
  };

  export const formatCPF = v => {
    if (!v) return v;
  
    v = v.toString().replace(/\D/g, "");
    v = v.replace(/^(\d{3})(\d)/g, "$1.$2");
    v = v.replace(/(\d{3})(\d{3})/g, "$1.$2");
    v = v.replace(/(\d)(\d{2})$/, "$1-$2");
    return v;
  };

  export const formatCEP = v => {
    if (!v) return v;
  
    v = v.toString().replace(/\D/g, "");
    v = v.replace(/(\d)(\d{3})$/, "$1-$2");
    return v;
  };
  