
/**
 * La función convierte HTML en un objeto jQuery analizando el HTML en un objeto DOM y manejando
 * recursivamente cada nodo para crear un nuevo elemento jQuery con los mismos atributos y nodos
 * secundarios.
 * @param html - una cadena que contiene código HTML para convertirlo en un objeto jQuery.
 * @param AcceptDomPurify
 * @param sinContenedor
 * @returns un objeto jQuery que contiene el HTML analizado a partir de la cadena de entrada. El HTML
 * se convierte en un objeto DOM usando $.parseHTML(), y luego cada nodo en el DOM se maneja
 * recursivamente y se convierte en un nuevo elemento jQuery usando handleNode(). Finalmente, todos los
 * nodos convertidos se agregan a un nuevo elemento jQuery vacío y se devuelven como resultado.
 *
 * @author Matias Diaz
 */
function convertHtmlToJQueryObject(html, AcceptDomPurify = true, sinContenedor = true) {
    let dom;

    // Parsea el HTML a un objeto DOM
    if (AcceptDomPurify) {
        dom = $.parseHTML(DOMPurify.sanitize(html));
    } else {
        dom = $.parseHTML(html);
    }

    // Función auxiliar recursiva para manejar cada nodo del DOM
    function handleNode(node) {
        if (node.nodeType === 3) { // Node.TEXT_NODE
            return node.nodeValue;
        } else if (node.nodeType === 1) { // Node.ELEMENT_NODE
            // Crear un nuevo elemento con el mismo tipo de nodo
            let el = $(document.createElement(node.nodeName.toLowerCase())); // Modificado para usar document.createElement()

            // Copiar todos los atributos
            $.each(node.attributes, function (i, attribute) {
                let nameClean = DOMPurify.sanitize(attribute.name);
                let valueClean = DOMPurify.sanitize(attribute.value, {'IN_PLACE': true});
                el.attr(nameClean, valueClean);
            });

            // Manejar todos los nodos hijos de manera recursiva
            $.each(node.childNodes, function (i, childNode) {
                let childNodeClean = DOMPurify.sanitize(handleNode(childNode), {'IN_PLACE': true});
                el.append(childNodeClean);

            });

            return el;
        }
    }

    if (sinContenedor) {
        // Crear un nuevo elemento jQuery vacío para contener los nodos convertidos
        let result = $('<div>');

        // Convertir cada nodo en el DOM parseado y agregarlo al resultado
        $.each(dom, function (i, node) {
            let nodeClean = DOMPurify.sanitize(handleNode(node), {'IN_PLACE': true});
            result.append(nodeClean);

        });

        // Devolver el resultado como un objeto jQuery
        return result;

    } else {
        // Crear un array para contener los nodos convertidos
        let result = [];

        // Convertir cada nodo en el DOM parseado y agregarlo al resultado
        $.each(dom, function (i, node) {
            result.push(handleNode(node));
        });

        // Devolver el resultado como un array de nodos jQuery
        return result;
    }
}
