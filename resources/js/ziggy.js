const Ziggy = {"url":"http:\/\/heblly-backend.test","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"posts.index":{"uri":"api\/posts","methods":["GET","HEAD"]},"posts.store":{"uri":"api\/posts","methods":["POST"]},"posts.show":{"uri":"api\/posts\/{post}","methods":["GET","HEAD"]},"posts.update":{"uri":"api\/posts\/{post}","methods":["PUT","PATCH"]},"posts.destroy":{"uri":"api\/posts\/{post}","methods":["DELETE"]},"gifts.index":{"uri":"api\/gifts","methods":["GET","HEAD"]},"gifts.show":{"uri":"api\/gifts\/{gift}","methods":["GET","HEAD"],"bindings":{"gift":"id"}},"gifts.update":{"uri":"api\/gifts\/{gift}","methods":["PUT","PATCH"],"bindings":{"gift":"id"}},"picks.index":{"uri":"api\/picks","methods":["GET","HEAD"]},"picks.store":{"uri":"api\/picks","methods":["POST"]},"picks.show":{"uri":"api\/picks\/{pick}","methods":["GET","HEAD"],"bindings":{"pick":"id"}},"picks.update":{"uri":"api\/picks\/{pick}","methods":["PUT","PATCH"],"bindings":{"pick":"id"}},"picks.destroy":{"uri":"api\/picks\/{pick}","methods":["DELETE"],"bindings":{"pick":"id"}},"hello":{"uri":"app\/hello","methods":["GET","HEAD"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
