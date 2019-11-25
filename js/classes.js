class Card {
  constructor(imgprov, tipocrd, cnumber, nombres, code_qr) {
    this.imgprov = imgprov;
    this.tipocrd = tipocrd;
    this.msjtipo = (tipocrd=='giftcard') ? 'Tarjeta de regalo' : 'Tarjeta prepagada';
    this.cnumber = cnumber;
    this.numcard = cnumber.substr(0,4)+' '+cnumber.substr(4,4)+' '+cnumber.substr(8,4)+' '+cnumber.substr(12,4);
    this.nombres = nombres;
    this.code_qr = code_qr;
    this.icongft = (tipocrd=='giftcard') ? "./ico.jpg" : "";
  }
}

class Campo {
  /*
    mrc_id        : Identificador en el dom del marco
    etq_id        : Identificador en el dom de la etiqueta
    etq_txt       : Texto de la etiqueta
    cmp_id        : Identificador en el dom del input
    cmp_clase     : clase de campo para los estilos
    cmp_tipo      : Tipo de input
    cmp_size      : Tamaño (de ancho) en caracteres del input
    cmp_maxlength : Longitud máxima del input
  */
  constructor(mrc_id,mrc_clase,etq_id,etq_clase,etq_txt,cmp_id,cmp_clase,cmp_tipo,cmp_size,cmp_maxlength) {
    // Marco
    this.mrc_id = mrc_id;
    this.mrc_clase = mrc_clase;
    // Etiqueta
    this.etq_id = etq_id;
    this.etq_clase = etq_clase;
    this.etq_txt = etq_txt;
    // Campo
    this.cmp_id = cmp_id;
    this.cmp_clase = cmp_clase;
    this.cmp_tipo = cmp_tipo;
    this.cmp_size = cmp_size;
    this.cmp_maxlength = cmp_maxlength;

    // Construcción de los campos
    let marco, label, field;
    // variables auxiliares
    let txtlabel;
    marco = document.createElement("div");
    marco.id = this.mrc_id;
    marco.classList.add(this.mrc_clase);

    txtlabel = document.createTextNode(this.etq_txt);
    label = document.createElement("span");
    label.id = this.etq_id;
    label.classList.add(this.etq_clase);
    label.appendChild(txtlabel);

    field = document.createElement("input");
    field.id = this.cmp_id;
    field.classList.add(this.cmp_clase);

    field.type = this.cmp_tipo;
    field.size = this.cmp_size;
    field.style.maxlength = this.cmp_maxlength;

    marco.appendChild(label);
    marco.appendChild(field);
    return marco;
  }
}
