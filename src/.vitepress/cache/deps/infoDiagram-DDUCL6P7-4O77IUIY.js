import {
  parse
} from "./chunk-OE67T74V.js";
import "./chunk-6B7BZQDJ.js";
import "./chunk-ODBSCUQ2.js";
import "./chunk-BQJ53AME.js";
import "./chunk-W7YMX5HA.js";
import "./chunk-WFO3HW65.js";
import "./chunk-WNVO6JFR.js";
import "./chunk-SLY7KJG4.js";
import "./chunk-OPWGNGDZ.js";
import {
  package_default
} from "./chunk-QZXXEOWZ.js";
import {
  selectSvgElement
} from "./chunk-BQQGYXZM.js";
import "./chunk-W4C6O4J6.js";
import "./chunk-2VRVB2MD.js";
import {
  __name,
  configureSvgSize,
  log
} from "./chunk-JC2JZGKJ.js";
import "./chunk-4UTD2NOI.js";
import "./chunk-FDBJFBLO.js";

// node_modules/mermaid/dist/chunks/mermaid.core/infoDiagram-DDUCL6P7.mjs
var parser = {
  parse: __name(async (input) => {
    const ast = await parse("info", input);
    log.debug(ast);
  }, "parse")
};
var DEFAULT_INFO_DB = {
  version: package_default.version + (true ? "" : "-tiny")
};
var getVersion = __name(() => DEFAULT_INFO_DB.version, "getVersion");
var db = {
  getVersion
};
var draw = __name((text, id, version) => {
  log.debug("rendering info diagram\n" + text);
  const svg = selectSvgElement(id);
  configureSvgSize(svg, 100, 400, true);
  const group = svg.append("g");
  group.append("text").attr("x", 100).attr("y", 40).attr("class", "version").attr("font-size", 32).style("text-anchor", "middle").text(`v${version}`);
}, "draw");
var renderer = { draw };
var diagram = {
  parser,
  db,
  renderer
};
export {
  diagram
};
//# sourceMappingURL=infoDiagram-DDUCL6P7-4O77IUIY.js.map
