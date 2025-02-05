from lxml import etree


xsd_path = "/home/felipe/ipti/br.tag/app/modules/sagres/soap/Educacao.xsd" #altere o caminho para a localização na sua máquina do xsd
xml_path = "/home/felipe/Downloads/Educacao.xml" #altere o caminho para a localização do XML gerado com suas alterações no Sagres


# Função para validar XML com o XSD principal apenas

def validate_xml_with_main_xsd_only(main_xsd_file, xml_file):

    try:

        # Parse do esquema XSD principal

        with open(main_xsd_file, 'r') as main_xsd:

            schema_root = etree.XML(main_xsd.read())

        schema = etree.XMLSchema(schema_root)



        # Parse do arquivo XML

        with open(xml_file, 'r') as xml:

            xml_doc = etree.parse(xml)



        # Validação

        schema.assertValid(xml_doc)

        return "O XML é válido em relação ao XSD principal.", None

    except etree.DocumentInvalid as e:

        return "O XML é inválido em relação ao XSD principal.", str(e)



# Validar XML com o XSD principal apenas

validation_result_main_only, error_details_main_only = validate_xml_with_main_xsd_only(xsd_path, xml_path)

validation_result_main_only, error_details_main_only
