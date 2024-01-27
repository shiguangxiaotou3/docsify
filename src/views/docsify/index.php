<?php
/** @var array $main  */


use \Shiguangxiaotou3\Docsify\assets\AceAsset;
use \Shiguangxiaotou3\Docsify\Docsify;
use \Shiguangxiaotou3\Docsify\assets\VueAsset;

wp_enqueue_script("jquery");
wp_enqueue_media();
AceAsset::register();
VueAsset::register();

$main = json_encode($main );
?>


<div id="screen-meta" class="metabox-prefs" :style="metaboxStyle">
    <!-- plugins -->
    <div id="contextual-help-wrap" v-if="active =='config'">
        <div id="contextual-help-back"></div>
        <div id="contextual-help-columns">
            <div class="contextual-help-tabs">
                <ul>
                    <li
                        :class="configTab === ''?'active':''"
                        @click="configTab = ''">
                        <a >配置</a>
                    </li>
                    <li
                        :class="configTab === 'themes'?'active':''"
                        @click="configTab = 'themes'">
                        <a >主题</a>
                    </li>
                    <li
                        :class="configTab === 'template'?'active':''"
                        @click="configTab = 'template'">
                        <a >模版</a>
                    </li>
                    <li
                        :class="configTab === 'plugins'?'active':''"
                        @click="configTab = 'plugins'">
                        <a >插件</a>
                    </li>
                    <li
                        :class="configTab === 'html'?'active':''"
                        @click="configTab = 'html'">
                        <a >html</a>
                    </li>
                </ul>
            </div>
            <div class="contextual-help-sidebar">
                <p><strong>操作：</strong></p>
                <p>
                    <button
                        type="button"
                        aria-disabled="false"
                        aria-expanded="false"
                        class="button button-primary"
                        @click="saveConfig">发布</button></p>
            </div>
            <div class="contextual-help-tabs-wrap">
                <h3 v-html="{'': '配置','template': '模版','html':'html', 'themes':'主题','plugins':'插件列表'}[configTab]"></h3>
                <div ref="configNotice" style="margin: 0 -20px;padding: 0  5px 5px 5px"></div>
                <template v-if="['', 'template', 'html'].includes(configTab)">
                    <ace-editor
                        :mode="codeEditor.mode"
                        :value="codeEditor.value"
                        :style="codeEditor.style"
                        @save="saveMainConfig"></ace-editor>
                </template>

                <!-- 主题  -->
                <template v-if="configTab === 'themes'">
                    <label :for="item" v-for="item in  Object.keys(main.themes)">
                        <input
                            type="radio"
                            :name="item"
                            :id="item"
                            v-model="themesActive"
                            :value="item">
                        {{StringHelper.capitalizeFirstLetter(item)}}
                    </label>
                </template>


                <!-- 插件  -->
                <template v-if="configTab === 'plugins'" >
                    <!-- from  -->
                    <api-wrap :title="modalTitle" :active="modalActive" @close="()=>{modalActive = !modalActive;}">
                        <div class="attachment-media-view landscape">
                            <div class="thumbnail">
                                <div class="attachment-actions" style="margin-top: 20px">
                  <textarea
                      id="wp-config"
                      style="width: 100%"
                      :rows="JSON.stringify(pluginsFromObject,null,4).split('\n').length"
                      class="code"
                      readonly="readonly"
                      v-html="JSON.stringify(pluginsFromObject,null,4)"
                      aria-describedby="wp-config-description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="attachment-info">
                            <div class="details">
                                <h2 class="screen-reader-text">详情</h2>
                                <div><strong>说明：</strong>
                                    插件启用后会将插件配置合并至docsify配置中
                                </div>
                                <div>
                                    <strong>资源包：</strong>
                                    资源包别名将自动替换为发布目录
                                </div>
                                <div>
                                    <strong>多文件：</strong>
                                    多个css js 请使用换行分隔
                                </div>
                                <div>
                                    <strong>DEBUG：</strong>
                                    WP_DEBUG = TRUE 将尝试路由"/index.min.css"替换为"/index.min.css"
                                </div>
                            </div>

                            <div class="settings">
                <span class="setting">
                      <label class="name" for="pluginsFrom.name" style="min-width: 10%;">名称</label>
                      <input
                          id="pluginsFrom.name"
                          type="text"
                          v-model="pluginsFrom.name"
                          name="pluginsName" placeholder="" style="width: 85%" value="name">
                </span>

                                <span class="setting">
                  <label class="name" for="pluginsFrom.usage" style="min-width: 10%;">配置</label>
                  <textarea
                      name="usage"
                      id="pluginsFrom.usage"
                      v-model="pluginsFrom.usage"
                      style="width: 85%;height: 150px"
                      placeholder="{&#10;    &quot;search&quot;: {&#10;        &quot;maxAge&quot;: &quot;86400000&quot;,&#10;        &quot;placeholder&quot;: &quot;Type to search&quot;&#10;    }&#10;}"></textarea>
                </span>

                                <span class="setting">
                  <label class="name" for="pluginsFrom.describe" style="min-width: 10%;">描述</label>
                  <textarea
                      type="text"
                      v-model="pluginsFrom.describe"
                      name="describe"
                      id="pluginsFrom.describe"
                      placeholder="描述..." style="width: 85%" value=""> </textarea>
                </span>

                                <span class="setting">
                  <label for="pluginsFrom.home" class="name" style="min-width: 10%;">网站</label>
                    <input
                        type="text"
                        id="pluginsFrom.home"
                        name="home"
                        v-model="pluginsFrom.home"
                        placeholder="http or https ..."
                        style="width: 85%" value="">
                </span>

                                <span class="setting">
                  <label for="pluginsFrom.md" class="name" style="min-width: 10%;">README</label>
                  <input
                      type="text"
                      id="pluginsFrom.md"
                      name="md"
                      v-model="pluginsFrom.md"
                      placeholder="https://domain.com/README.md"
                      style="width: 85%" value="">
                </span>

                                <span class="setting">
					<label for="pluginsFrom.assetsCss" class="name" style="min-width: 10%;">Css</label>
					<textarea
                        id="pluginsFrom.assetsCss"
                        v-model="pluginsFrom.assetsCss"
                        placeholder="https://domain.com/index.css"
                        style="width: 85%;height: 80px"></textarea>
				</span>

                                <span class="setting">
					<label for="pluginsFrom.assetsJs" class="name" style="min-width: 10%;">Js</label>
					<textarea
                        id="pluginsFrom.assetsJs"
                        v-model="pluginsFrom.assetsJs"
                        placeholder="https://domain.com/index.js"
                        style="width: 85%;height: 80px"></textarea>
				</span>
                            </div>
                            <div class="actions">
                                <button type="button" class="button show-settings" @click="savePlugins">
                                    保存
                                </button>
                            </div>
                        </div>
                    </api-wrap>

                    <button type="button" class="button show-settings" @click="createPlugins">
                        创建
                    </button>
                    <table
                        v-if="files[fileTab].file.length>0"
                        class="wp-list-table widefat fixed striped table-view-list"
                        style="margin-top: 5px;">
                        <thead>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <label for="cb-select-all-1" class="screen-reader-text">全选</label>
                                <input id="cb-select-all-1" v-model="pluginsSelectAll" @change="pluginsSelectAllInput" name="selectAll"
                                       type="checkbox">
                            </td>
                            <td style="width: 200px">名称</td>
                            <td>插件描述</td>
                            <td style="width: 150px">操作</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="plugin in Object.keys(main.plugins)">
                            <th scope="row" class="check-column">
                                <label :for="plugin" class="screen-reader-text">启动</label>
                                <input type="checkbox" v-model="main.pluginsEnable" :value="plugin" :id="plugin" name="pluginsEnable">
                                <div class="locked-indicator">
                                    <span aria-hidden="true" class="locked-indicator-icon"></span>
                                </div>
                            </th>
                            <td class="manage-column">{{StringHelper.capitalizeFirstLetter(plugin)}}</td>
                            <td class="manage-column">{{main.plugins[plugin].describe}}</td>

                            <td class="manage-column">
                                <!-- home -->
                                <a
                                    style="background-color:  #f6f7f7; color: #2271b1; border: 1px solid #2271b1;  padding: 2px; border-radius: 5px;"
                                    v-if="main.plugins[plugin].home !=''"
                                    class="dashicons dashicons-admin-links"
                                    :href="main.plugins[plugin].home"
                                    target="_blank"></a>
                                <!-- markdown -->
                                <a
                                    v-if="main.plugins[plugin].md !=''"
                                    style="background-color:  #f6f7f7; color: #2271b1; border: 1px solid #2271b1;  padding: 2px; border-radius: 5px;"
                                    class="dashicons dashicons-editor-contract"
                                    @click="isPluginsReadme(plugin,main.plugins[plugin])"></a>
                                <!-- editor -->
                                <a
                                    class="dashicons dashicons-welcome-edit-page"
                                    style="background-color:  #f6f7f7; color: #2271b1; border: 1px solid #2271b1;  padding: 2px; border-radius: 5px;"
                                    @click="editorPlugins(plugin)"></a>
                                <!-- delete -->
                                <a
                                    style="background-color:  #f6f7f7; color: #b32d2e; border: 1px solid #2271b1;  padding: 2px; border-radius: 5px;"
                                    class="dashicons dashicons-dismiss"
                                    @click="deletePlugins(plugin)"></a>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </template>
                <p></p>
            </div>
        </div>
    </div>
    <!-- plugins -->

    <!-- 文件 -->
    <div id="contextual-help-wrap" v-if="active =='file'">
        <div id="contextual-help-back"></div>
        <div id="contextual-help-columns">
            <div class="contextual-help-tabs">
                <ul>
                    <li class="active">
                        <a @click="fileTab = ''">{{fileTab.split('/').filter(Boolean).pop()||'目录'}}</a>
                    </li>
                    <li v-for="item in files[fileTab].dir"
                        :class="fileTab.startsWith(item.path)?'active':''"
                    >
                        <a @click="fileClick(item)"><span  class="dashicons dashicons-portfolio" ></span> {{StringHelper.capitalizeFirstLetter(item.name)}}</a>
                    </li>
                    <li v-if="fileTab!==''">
                        <a @click="fileUp"><span  class="dashicons dashicons-arrow-up-alt2"></span>返回上级</a>
                    </li>
                </ul>
            </div>
            <div class="contextual-help-sidebar">
                <p><strong>操作：</strong></p>
                <p><a @click="fileUp">返回上级</a></p>

            </div>
            <div class="contextual-help-tabs-wrap">
                <h3  class="wp-heading-inline">文件</h3>
                <div ref="fileNotice" style="margin: 0 -20px;padding: 0  5px 5px 5px"></div>

                <button type="button" class="button show-settings" @click="createFile">
                    创建
                </button>
                <button
                    type="button"
                    class="button "
                    @click="deleteFile(fileTab)">清空</button>

                <button
                    type="button"
                    aria-disabled="false"
                    aria-expanded="false"
                    class="button button-primary"
                    @click="publishMarkdown">发布</button>
                <table
                    v-if="files[fileTab].file.length>0"
                    class="wp-list-table widefat fixed striped table-view-list"
                    style="margin-top: 5px;">
                    <thead>
                    <tr>
                        <td>文件名称</td>
                        <td>权限</td>
                        <td>所有者</td>
                        <td>所有组</td>
                        <td>文件大小</td>
                        <td>修改时间</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in files[fileTab].file " @dblclick.stop="editorFile(fileTab+'/'+item.name)">
                        <td class="manage-column">{{item.name}}</td>
                        <td class="manage-column">{{item.permissions}}</td>
                        <td class="manage-column">{{item.owner}}</td>
                        <td class="manage-column">{{item.group}}</td>
                        <td class="manage-column">{{item.size}}</td>
                        <td class="manage-column">{{item.modified_time}}</td>
                        <td class="manage-column">
                            <!-- editor -->
                            <a
                                class="dashicons dashicons-welcome-edit-page"
                                style="background-color:  #f6f7f7; color: #2271b1; border: 1px solid #2271b1;  padding: 2px; border-radius: 5px;"
                                @click="editorFile(fileTab+'/'+item.name)"></a>
                            <!-- delete -->
                            <a
                                style="background-color:  #f6f7f7; color: #b32d2e; border: 1px solid #2271b1;  padding: 2px; border-radius: 5px;"
                                class="dashicons dashicons-dismiss"
                                @click="deleteFile(fileTab+'/'+item.name)"></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <p></p>
            </div>
        </div>
    </div>
    <!-- 文化 -->
</div>

<div id="screen-meta-links" style="display: flex;">
    <div
        v-for="(item, index) in buttons"
        class="hide-if-no-js screen-meta-toggle"
        :style="buttonStyle(item.id, index)"
        @click="buttonChange(item.id)">
        <button type="button"
                :id="item.id === active ? 'show-settings-link' : ''"
                :class="item.id !== active ? 'button show-settings' : 'button show-settings screen-meta-active'">
            {{ item.text }}
        </button>
    </div>
</div>

<div class="wrap">
    <h1 class="wp-heading-inline">{{title}}</h1>
    <ul class="nav-tab-wrapper wp-clearfix">
        <a :class="tab=='editor' ? 'nav-tab nav-tab-active':'nav-tab'"  @click="tab='editor'">编辑</a>
        <a :class="tab=='iframe' ? 'nav-tab nav-tab-active':'nav-tab'" @click="tab='iframe'">预览</a>
    </ul>
    <div style="display: flex;min-height: 500px;" id="content">
        <ace-editor
            :ext="AceEditor.ext"
            :theme="AceEditor.theme"
            :mode="AceEditor.mode"
            :value="AceEditorFileText"
            :is-show="AceEditor.isShow"
            v-show="tab=='editor'"
            @save="saveFile"
        ></ace-editor>
        <docsify-iframe
            ref="view"
            :html-template="docsifyTemplate"
            v-show="tab=='iframe'"
            :css="css"
            :js="js"
            :code="code"
            :alias="main.alias"></docsify-iframe>
    </div>
</div>


<?php
(Docsify::App()->assets)['code']=<<<JS

/**
 * @typedef {Object} AceEditorProps 接收参数
 * @property {string} ext - Ace编辑器拓展
 * @property {string} theme - Ace编辑器主题
 * @property {string} mode - Ace编辑器模式
 * @property {string} value - 初始文本内容
 */

/**
 * @typedef {Object} VueComponent
 */

/**
 * @typedef {Object} ace
 * @property {function(string): ace} require - 加载模块
 * @property {function(string)} edit - 编辑指定元素
 * @memberof this
 */

/**
 * Vue 实例的属性
 * @property {Object} \$attrs - 包含了父作用域中不被prop所识别(且获取)的特性绑定(class 和 style 除外)
 * @property {VueComponent[]} \$children - 当前实例的直接子组件数组
 * @property {function(*)} \$createElement - 创建虚拟节点的函数，可用于手动渲染函数
 * @property {string} \$el - 当前实例的根 DOM 元素
 * @property {Object} \$listeners - 包含了父组件传递给当前组件的所有事件监听器
 * @property {Object} \$options - 当前实例的初始化选项,包括生命周期、数据、计算属性、方法、watcher 等
 * @property {Object} \$parent - 父实例，如果当前实例有的话
 * @property {Object} \$refs - 包含了所有拥有 ref 注册的子组件和元素的引用
 * @property {Vue} \$root - 当前组件树的根Vue实例
 * @property {Object} \$scopedSlots - 当前组件的插槽,包括具名插槽和作用域插槽
 * @property {Object} \$slots - 包含了所有插槽的对象,以插槽名为 key
 * @property {Object} \$vnode - Vue 实例的占位符节点,用于手动创建注释节点或空节点
 * @property {Object} \$emit -  绑定的事件
 * @memberof this
 */

/**
 * @typedef {Object} commandConfig - 按键绑定配置对象
 * @property {string} name - 按键名称
 * @property {Object} bindKey - 配置的组合按键
 * @property {function(AceObject)} exec - 按键执行回调
 * @property {boolean} readOnly - 绑定的按键对象
 */

/**
 * @typedef {Object} commandObject - 绑定按键配置
 * @property {function(commandConfig[])} addCommands - 绑定的按键对象
 */

/**
 * @typedef {Object} session Ace编辑器会话
 * @property {function(string)} setMode - 设置Ace编辑器模式
 * @property {function(string,function(Event))} on - 绑定事件
 */

/**
 * @typedef {Object} AceObject ace实例
 * @property {function(): session} getSession - 获取Ace编辑器session
 * @property {function(string)} setTheme - 设置Ace编辑器主题
 * @property {Object} session - Ace编辑器会话
 * @property {commandObject} commands - 按键对象
 * @property {function()} showSettingsMenu - 打开设置页面
 * @property {function():string} getValue - 获取编辑文本
 * @property {function(string)} setValue - 设置编辑文本
 * @property {function(string)} getOption - 获取属性值
 * @property {function(string,any)} setOption - 设置数据值
 * @property {function():void} resize - 重载
 */

/**
 * @typedef {Object} Vue
 * @property {function(string,Object)} component - 创建组件
 * @property {function(Object)} use - 挂载组件
 * @memberof Vue
 */

Vue.component("ace-editor", {
    /**
     * Ace编辑器组件模板
     * @type {string}
     */
    template: `
<pre
    v-show="isShow"
    :id="elId"
    style="margin-bottom: 0px;width: 100%;height: 100%;">
</pre>
`,
    /**
     * 组件数据
     * @returns {Object}
     */
    data() {
        return {
            /**
             * @type {string}
             */
            iframeHtml: ``,
            /**
             * Ace编辑器实例
             * @type {Object} AceObject
             */
            editor:{},
            id:''
        };
    },
    computed: {
        elId(){
            if(this.id ==''){
                this.id =this.generateRandomString(8)
            }
            return this.id
        }
    },
    /**
     * 组件属性
     * @type {AceEditorProps}
     */
    props: {
        /**
         * Ace编辑器拓展
         * @type {string}
         */
        ext: {
            type: String,
            default: "ace/ext/settings_menu",
        },
        /**
         * Ace编辑器主题
         * @type {string}
         */
        theme: {
            type: String,
            default: "ace/theme/monokai",
        },
        /**
         * Ace编辑器模式
         * @type {string}
         */
        mode: {
            type: String,
            default: "ace/mode/markdown",
        },
        /**
         * 初始文本内容
         * @type {string}
         */
        value: {
            type: String,
            default: '',
        },
        isShow: {
            type: Boolean,
            default: true,
        },
    },
    /**
     * @type {Object}
     */
    methods: {
        aceInit(){
            /**
             * 创建 Ace 编辑器实例
             * @type {Object} AceObject
             */
            this.editor = this.ace.edit(this.elId);
            this.ace.require(this.ext).init(this.editor);
            this.editor.getSession().on("change", (e) => {
                // this.change(e)
            });

            this.editor.setTheme(this.theme);
            this.editor.session.setMode(this.mode);
            this.editor.setValue(this.value)
            this.editor.selection.moveCursorTo(0, 0);
            this.editor.commands.addCommands([
                {
                    name: "showSettingsMenu",
                    bindKey: { win: "Ctrl-q", mac: "Ctrl-q" },
                    exec: (editor) => {
                        editor.showSettingsMenu();
                        let settingsMenu = document.getElementById("ace_settingsmenu");
                        if (settingsMenu) {
                            settingsMenu.style.padding = "3em 0.5em 2em 1em";
                        }
                    },
                    readOnly: true
                },
                {
                    bindKey: { win: "Ctrl-s", mac: "Ctrl-s" },
                    exec: this.aceSave,
                    readOnly: true
                }
            ]);
            window.onload = () => {
                this.editor.resize();
            }
        },
        /**
         *
         * @param editor AceObject
         */
        aceSave(editor){
            if( this.\$listeners['save']){
                this.\$emit('save',editor)
            }
        },
        generateRandomString(length=8) {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                result += characters.charAt(randomIndex);
            }

            return result;
}
    },
    watch:{
        ext(value){
            if(this.editor){
                this.ace.require(value).init(this.editor);
                this.editor.session.setMode(value);
                this.editor.resize();
            }
        },
        theme(value){
            if(this.editor){
                this.editor.setTheme(value);
                this.editor.resize();
            }
        },
        mode(value){
            if(this.editor){
                this.editor.session.setMode(value);
                this.editor.resize();
            }

        },
        value(value){
            if(this.editor){
                this.editor.setValue(value)
                this.editor.selection.moveCursorTo(0, 0);
                this.editor.resize();
            }
        }
    },
    mounted(){
         this.aceInit();
    }
});


/**
 * Vue组件：api-meta
 */
Vue.component("api-meta", {
    template: `
<div>    
    <div id="screen-meta" class="metabox-prefs" :style="metaboxStyle">
        <slot :index="active"></slot>
    </div>
    <div id="screen-meta-links" style="display: flex">
        <div  
            v-for="(item, index) in buttons"  
            class="hide-if-no-js screen-meta-toggle" 
            :style="buttonStyle(item.id, index)" 
            @click="buttonChange(item.id)">
            <button type="button"
                :id="item.id === active ? 'show-settings-link' : ''"
                :class="item.id !== active ? 'button show-settings' : 'button show-settings screen-meta-active'">
                {{ item.text }}
            </button>
        </div>
    </div>
</div>`,
    data() {
        return {
            /**
             * 当前激活的按钮id
             * @type {string}
             */
            active: ""
        };
    },
    watch: {},
    props:{
        buttons: {
            type: Array,
            default: [],
        }
    },
    computed: {
        /**
         * 根据激活状态动态设置metabox的样式
         * @returns {Object}
         */
        metaboxStyle() {
            return {
                display: this.active === "" ? "" : "block"
            }
        }
    },

});

/**
 *
 * @typedef {Object} Asset - 资源包
 * @property {string} assetsName - 资源的名称。
 * @property {string} publishUrl - 资源应发布的URL。
 */

/**
 * @typedef {Object} DocsifyIframeProps 接收参数
 * @property {string[]} css - CSS 文件的数组。
 * @property {string[]} js - JavaScript 文件的数组。
 * @property {Asset[]} assets - 资源包数组。
 * @property {Object} config - 配置对象。
 */

Vue.component("docsify-iframe", {
    /**
     * @type {string}
     */
    template: `
<iframe
    ref="myFrame"
    title="编辑器画布"
    :style="iframeStyle">
</iframe>`,
    /**
     * @returns {Object}
     */
    data() {
        return {
            /**
             * @type {string}
             */
            iframeHtml: `<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta charset="UTF-8" />
    <title></title>
  </head>
  <body>
  </body>
</html>`,
        };
    },
    /**
     * @type {Object}
     */
    computed: {
        /**
         * @returns {string}
         */
        cssLink() {
            /**
             * @type {array}
             */
            let link = [];
            for (let i = 0; i <= this.css.length - 1; i++) {
                /**
                 * @type {string}
                 */
                let item = this.css[i];
                link.push( this.createElementHtml('link',"",{rel:'stylesheet',href:item}));
            }
            return link.join('\\n    ');
        },
        jsLink(){
            /**
             * @type {array}
             */
            let link = [];
            for (let i = 0; i <= this.js.length - 1; i++) {
                /**
                 * @type {string}
                 */
                let item = this.js[i];
                link.push(this.createElementHtml('script',"",{src:item,type: 'text/javascript'}))  // this.createElementHtml('script',"",{src:item,type: 'text/javascript'})+'\\n';
            }
            return link.join('\\n    ');
        },
        codeLink(){

            return this.createElementHtml(
                'script',
                this.code,
                {type: 'text/javascript'}
            );
        },
        /**
         * @returns {string}
         */
        html() {
            const cssRegex = new RegExp('<!-- CSS_FILE -->', 'g');
            const jsRegex = new RegExp('<!-- JS_FILE -->', 'g');
            const codeRegex = new RegExp('<!-- CODE -->', 'g');
            /**
             * @type {string}
             */
            let content = this.htmlTemplate;
            content = content.replace(cssRegex, this.cssLink);
            content = content.replace(jsRegex, this.jsLink);
            content = content.replace(codeRegex, this.codeLink);
            for (let i = 0; i <= this.alias.length - 1; i++) {
                /**
                 * @type {Asset}
                 */
                let item = (this.alias)[i];
                /**
                 * @type {RegExp}
                 */
                let regex = new RegExp(item.name, 'g');
                content = content.replace(regex, item.url);
            }

            return content;
        },
    },
    watch:{
        css(){
            this.renderIframe()
        },
        js(){
            this.renderIframe()
        },
        assets(){
            this.renderIframe()
        },
        config(){
           this.renderIframe()
        }
    },
    /**
     * @type {DocsifyIframeProps}
     */
    props: {
        /**
         * 接收css
         * @type {string[]}
         */
        css: {
            type: Array,
            default: ()=>{return []},
        },
        /**
         * @type {string[]}
         */
        js: {
            type: Array,
            default: ()=>{return []},
        },
        /**
         * @type {Asset[]}
         */
        alias: {
            type: Array,
            default: ()=>{return []},
        },
        /**
         * @type {string}
         */
        code: {
            type: String,
            default: "",
        },
        iframeStyle:{
            type: Object,
            default: ()=>{return {
                // marginTop: "5px",
                flex: 1,
                // border: "1px solid gray",
                width: "100%",
                minHeight: "500px",
                height: "100%"
            }},
        },
        htmlTemplate:{
            type: String,
            default: ()=>{return this.iframeHtml},
        }
    },
    /**
     * @type {Object}
     */
    methods: {
        /**
         * @returns {void}
         */
        renderIframe() {
            /**
             * @type {HTMLIFrameElement}
             */
            const iframe = this.\$refs.myFrame;
            /**
             * @type {Document}
             */
            const iframeDocument = iframe.contentDocument || iframe.contentWindow?.document;
            /**
             * @type {string}
             */
            let content = this.html;

            iframeDocument?.open();
            iframeDocument?.write('');
            iframeDocument?.close();
            iframeDocument?.open();
            iframeDocument?.write(content);
            iframeDocument?.close();
        },
        createElementHtml(tag,text="",options={}){
            let element = document.createElement(tag);
            for (let [key, value] of Object.entries(options)) {
                element.setAttribute(key, value);
            }
            if (text !== "") {
                element.innerHTML = text;
            }
            return  element.outerHTML;
        },
    },
    mounted(){
        this.renderIframe()
    }
});

/**
 * @typedef {function():jQuery} jQuery
 * @returns {HTMLElement}
 * @property {function(Object): Promise} ajax - 异步函数，执行 AJAX 请求
 * @memberof $
 */

/**
 * @typedef {string} ajaxurl ajax请求url
 * @default '/wp-admin/admin-ajax.php'
 */

/**
 * @typedef {string} adminpage 当前页面名称
 * @default 'toplevel_page_docsify'
 */

/**
 * @typedef {Object} adminMenu 菜单对象
 * @property {function()} favorites -
 * @property {function()} fold -
 * @property {function()} init - 初始化
 * @property {function()} restoreMenuState - 注册状态
 * @property {function()} toggle - 切换控制
 */

/**
 * @typedef {Object}  wp  - wordpress的js api
 * @property {Module} hooks - 模块
 * @property {function(Object)} media - 媒体
 * @property {Object} mce - 是WordPress中的TinyMCE编辑器的JavaScript API
 * @property {Module} i18n - 国际化
 * @property {Object} mediaelement - 媒体
 * @property {Module} a11y - 是WordPress中的Accessibility工具库,
 * @property {Object} ajax - wordpress的xhr请求
 * @property {function(Object)} apiRequest - rest api
 * @property {function(Object)} template
 */

/**
 * @typedef {Object} wpCookies cookies对象
 * @property {function(string,*,number)} each  - 变量
 * @property {function(string)} get  - 获取属性值
 * @property {function(string)} getHash - 获取哈希值
 * @property {function(string,*,*,*)} remove - 移除
 * @property {function(string,*,*,*,*,*)} set - 设置属性值
 * @property {function(string,*,*,*,*,*)} setHash - 设置哈希值
 */

/**
 * @typedef {Object}  wpApiSettings wordpress设置api
 * @property {string} nonce - 令牌
 * @property {string} root - 路径
 * @property {string} versionString - api版本
 */

/**
 * @typedef {Object} ace
 * @property {function(string): ace} require - 加载模块
 * @property {function(string)} edit - 编辑指定元素
 */


Vue.component("help-wrap", {
    template: `
<div
    id="contextual-help-wrap"
    v-if="show">
    <div id="contextual-help-back"></div>
    <div id="contextual-help-columns">
        <div class="contextual-help-tabs">
            <slot name="tabs"></slot>
            <ul>
                <li
                    v-for="item,index in tabs"
                    :class="activePlugins === item?'active':''"
                    @click="activePlugins=item">
                    <a v-html="StringHelper.capitalizeFirstLetter(item)"></a>
                </li>
            </ul>
        </div>

        <div class="contextual-help-sidebar">
             <slot name="sidebar"></slot>
        </div>
        <div class="contextual-help-tabs-wrap">
            <slot name="wrap"></slot>
        </div>
    </div>
</div>
`,
    data(){
        return {
            activePlugins:""
        }
    },

})


Vue.component('api-wrap', {
    template: `
<div v-show="active">
        <div tabindex="0" class="media-modal wp-core-ui" role="dialog" aria-labelledby="media-frame-title">
            <div class="media-modal-content" role="document">
                <div class="edit-attachment-frame mode-select hide-menu hide-router">
                    <!-- header -->
                    <div class="edit-media-header">
                        <button  class="left dashicons" :disabled="left"  @click="thisUp"></button>
                        <button class="right dashicons" :disabled="right"  @click="thisNext"></button>
                        <button type="button" class="media-modal-close" @click="thisClose"><span class="media-modal-icon"></span></button>
                    </div>
                    <div class="media-frame-title"><h1>{{title}}</h1></div>
                    <!-- end header -->
                    <!--  content -->
                    <div class="media-frame-content">
                        <div class="attachment-details save-ready">
                        <slot></slot>
<!--                            <div class="attachment-media-view landscape">-->
<!--                                <div class="thumbnail">-->
<!--                                    <div class="attachment-actions" style="margin-top: 20px">-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="attachment-info"></div>-->
                        </div>
                    </div>
                    <!-- end content -->
                </div>
            </div>
        </div>
        <div class="media-modal-backdrop"></div>
    </div>`,
    data() {
        return {
            isShow: true
        }
    },
    props: {
        title:{
            type: String,
            default: '测试弹窗',
        },
        active:{
            type:Boolean,
            default:false
        },
        delay:{
            type:Number,
            default:0
        }
    },
    watch:{
        delay(){
            if(this.active ==false){
                this.active =true;
                setTimeout(()=>{
                    this.active =false;
                },this.delay)
            }
        },
    },
    computed: {
        left(){
           return  !('up' in this.\$listeners) ;
        },
        right(){
            return  !('next' in  this.\$listeners) ;
        }
    },
    methods: {
        thisClose(e){
            if('close' in this.\$listeners){
                this.\$emit('close',e)

            }
        },
        thisUp(e){
            if('up' in this.\$listeners){
                this.\$emit('up',e)
            }
        },
        thisNext(e){
            if('next' in  this.\$listeners){
                this.\$emit('next',e)
            }
        }
    },
    mounted(){
    }
});


Vue.component("notice",{
    template: `
    <div class="notice  is-dismissible"> 
        <p><strong v-html="message"></strong></p>
        <button type="button" class="notice-dismiss" @click="thisClose"><span class="screen-reader-text">忽略此通知。</span></button>
    </div>    
`,
    props: {
        message:{
            type: String,
            default: '测试弹窗',
        },
        type:{
            type: String,
            default: '',
        }
    },
    methods: {
        thisClose(e){
            if('close' in this.\$listeners){
                this.\$emit('close',e)

            }
        }
    },
    mounted(){
        if(this.type !==''){
            this.\$el.classList.add(this.type);
        }

    }
})




Vue.component("modal",{
    template: `
<div class="notification-dialog-wrap request-filesystem-credentials-dialog" :style="active ?'display: block;':'display: none;'">
    <div class="notification-dialog-background" @click.stop="modalCancel"></div>
    <div class="notification-dialog" role="dialog">
        <div class="request-filesystem-credentials-dialog-content">
            <div class="request-filesystem-credentials-form">
            <h1>{{title}}</h1>
            <slot></slot>
            <p class="request-filesystem-credentials-action-buttons">
                <button v-if="this.\$listeners['cancel']" class="button cancel-button" data-js-action="close" type="button" @click="modalCancel" >取消</button>
                <button v-if="this.\$listeners['confirm']" class="button" type="button" @click="modalConfirm">确定</button>
                <input v-if="this.\$listeners['submit']" class="button" type="submit" @click="modalSubmit" value="提交">
            </p>
        </div>
    </div>
    </div>
</div> 
`,
    data(){
      return {

      }
    },
    props: {
        title:{
            type: String,
            default: '测试弹窗',
        },
        active:{
            type:Boolean,
            default: false,
        }
    },
    methods: {
        modalCancel(event){
            if( this.\$listeners['cancel']){
                this.\$emit('cancel',event)
            }
        },
        modalConfirm(event){
            if( this.\$listeners['confirm']){
                this.\$emit('confirm',event)
            }
        },
        modalSubmit(event){
            if( this.\$listeners['submit']){
                this.\$emit('submit',event)
            }
        }
    },
    mounted(){
    }
})
class ArrayHelper {
    constructor(config) {

    }

    /**
     * 根据数组元素的key和值,查找满足条件的元素
     * @param {array} array
     * @param {string} key
     * @param {*} value
     * @param {boolean} option
     * @return {*|false|*[]}
     */
    vlookup(array, key, value, option = true) {
        if (Array.isArray(array)) {
            if (option) {
                for (let i = 0; i <= array.length - 1; i++) {
                    if (array[i][key] === value) {
                        return array[i]
                    }
                }
            } else {
                let result = [];
                for (let i = 0; i <= array.length - 1; i++) {
                    if (array[i][key] === value) {
                        result.push(array[i])
                    }
                }
                return result.length > 0 && result;
            }
        }
    }


    /**
     * 替换数组中指定索引的元素
     * @param {Array} array - 输入数组
     * @param {number} index - 要替换的元素索引
     * @param {*} item - 替换后的元素
     * @returns {Array|false} - 替换后的新数组，如果输入不是数组则返回 false
     */
    replaceElement(array, index, item) {
        if (Array.isArray(array)) {
            const newArray = array.slice();
            newArray[index] = item;
            return newArray;
        }
        return false
    }

    /**
     * 根据键值对条件替换数组中的元素
     * @param {Array} array - 输入数组
     * @param {string} key - 指定键
     * @param {*} value - 指定值
     * @param {*} item - 替换后的元素
     * @returns {Array|false} - 替换后的新数组，如果输入不是数组则返回 false
     */
    replaceElementsByKeyValue(array, key, value, item) {
        if (Array.isArray(array)) {
            return array.map(row => (row[key] === value ? item : row));
        }

    }

    /**
     * 将对象的属性名和属性值转换为数组
     * @param {Object} object - 输入对象
     * @returns {Array|false} - 包含对象属性名和属性值的数组，如果输入不是对象则返回 false
     */
    objectToArray(object) {
        if (typeof object !== 'object' || object === null) {
            return false;
        }

        return Object.entries(object).map(([name, value]) => ({name, value}));
    }


    /**
     * 添加或替换数组中指定条件的元素。
     * @param {Array} array - 要处理的数组。
     * @param {string} key - 用于查找的属性名。
     * @param {*} value - 用于查找的属性值。
     * @param {*} item - 要添加或替换的新元素。
     * @return {Array} - 处理后的数组。
     */
    addOrReplaceItem(array, key, value, item) {
        if (Array.isArray(array)) {
            // 确保传入的参数是一个数组
            let existingItem = array.find(function (element) {
                return element[key] === value;
            });

            if (existingItem) {
                // 如果找到了，替换元素
                let index = array.indexOf(existingItem);
                array[index] = item;
            } else {
                // 如果没找到，添加新元素
                array.push(item);
            }
        }
        return array;
    }

}
class StringHelper {
    constructor(config){

    }

    /**
     * 将驼峰命令转化为首字母大写单词
     * @param {string} input
     * @returns {string}
     */
    capitalizeFirstLetter(input){
        return input
            .replace(/_/g, ' ')
            .replace(/[A-Z]/g, match => ` \${match}`)
            .replace(/^\s*/, '')
            .replace(/\s*$/, '')
            .toLowerCase()
            .replace(/(?:^|\s)\S/g, match => match.toUpperCase());
    }

    /**
     * 计算多行字符串行数
     * @param {string} input
     * @return {*|number}
     */
    countLines  (input)  {
        const lineMatches = input.match(/\\n/g);
        return lineMatches ? lineMatches.length + 1 : 1;
    }


    /**
     * 获取给定路径的目录部分。
     *
     * @param {string} filePath - 要处理的文件路径。
     * @param {number} [level=0] - 要移除的路径级别，默认为 0。
     * @returns {string|false} - 返回目录部分，如果无法获取则返回 false。
     */
    dirname(filePath, level = 0) {
        if (level === 0) {
            if (typeof filePath === 'string') {
                let index = filePath.lastIndexOf('/');
                if (index > 0) {
                    return filePath.substring(0, index);
                }
            }
            return false;
        } else {
            let result = filePath;
            for (let i = 0; i <= level; i++) {
                result = this.dirname(result);
            }
            return result;
        }
    }

}
class TimeHelper {
    constructor(config) {

    }

    /**
     * 返回当前时间戳
     * @return {number}
     */
    time() {
        return new Date().getTime();
    }

    /**
     * 时间戳转化为格式化的日期和时间字符串
     * @param {null|string|number} timestamp - 时间戳(可选),如果未提供将使用当前时间戳。
     * @param {null|string} format - 格式字符串,用于指定返回的日期和时间格式,默认为"Y-m-d H:i:s"。
     * @return {string} - 格式化的日期和时间字符串。
     */
    formatTimestamp(timestamp, format = "Y-m-d H:i:s") {
        const date = timestamp ? new Date(timestamp) : new Date();

        const formats = {
            'Y': date.getFullYear(),
            'm': String(date.getMonth() + 1).padStart(2, '0'),
            'd': String(date.getDate()).padStart(2, '0'),
            'H': String(date.getHours()).padStart(2, '0'),
            'i': String(date.getMinutes()).padStart(2, '0'),
            's': String(date.getSeconds()).padStart(2, '0')
        };
        return format.replace(/([YmdHis])/g, (match, p1) => formats[p1]);
    }
}

Vue.use({
    install(Vue) {
        Vue.prototype.jQuery = $;
        Vue.prototype.ajaxurl = ajaxurl
        Vue.prototype.wp = wp
        Vue.prototype.ArrayHelper = new ArrayHelper()
        Vue.prototype.StringHelper = new StringHelper()
        Vue.prototype.TimeHelper = new TimeHelper()
        Vue.prototype.ace = ace
        /**
         * 
         * @param {string} message - The message to be displayed.
         * @param {''  | 'notice-success' | 'notice-error' | 'notice-danger' | 'notice-warning'} type - The type of the notice.
         * @param 
         * @example
         * this.notice('This is a success message');
         * this.notice('This is a success message', 'notice-success');
         * this.notice('This is an error message', 'notice-error');
         * this.notice('This is a danger message', 'notice-danger');
         * this.notice('This is a warning message', 'notice-warning');
         */
        Vue.prototype.notice = function(message,type,callback){
           const notice =  Vue.extend({
                template:'<notice :message="message" :type="type" @close="close"></notice>',
                props: ['message','type']
            })
           const noticeInstance = new notice({
                propsData: {message: message,type:type}
              }
           );
           noticeInstance.close=()=>{
               noticeInstance.\$destroy(); // 销毁组件
               noticeInstance.\$el.remove();
           }
           if(callback && typeof callback === 'function'){
               callback(noticeInstance.\$mount().\$el)
           }else{
               const wrapElement = document.querySelector('.wrap');
               const h1Element = wrapElement.querySelector('h1');
               if (h1Element) {
                   wrapElement.insertBefore(noticeInstance.\$mount().\$el, h1Element.nextSibling);
               } else {
                   wrapElement.appendChild(noticeInstance.\$mount().\$el);
               }
           }
           
           
        }
        Vue.prototype.__ = wp.i18n.__
        Vue.prototype.confirm =  function(title){
            return new Promise((resolve, reject) => {
                const confirm =  Vue.extend({
                    template:`<modal :title="title" :active="active"   @cancel="cancel" @confirm="confirm">
                    <label  for="input">
                        <span class="field-title"></span>
                        <input  type="text" name="input" class="code" placeholder="" v-model="confirmText" @keyup.enter="confirm" value="">
                    </label>
                    </notice>`,
                    props: ['title','active']
                })
                const confirmInstance = new confirm({
                    propsData: {title: title,active:true}
                });
                confirmInstance.confirmText = this.fileTab
                confirmInstance.cancel=()=>{
                   confirmInstance.\$destroy(); // 销毁组件
                   confirmInstance.\$el.remove();
                   resolve(false)
               }
                confirmInstance.confirm= (e)=>{
                    confirmInstance.\$destroy(); // 销毁组件
                    confirmInstance.\$el.remove();
                    resolve(confirmInstance.confirmText) 
                }
                this.\$el.appendChild(confirmInstance.\$mount().\$el)
            });
            
        }
    }
})

new Vue({
    el: '#wpbody-content',
    data(){
        return {
            // 帮助栏
            active:"",
            title: "Docsify",
            tab:"editor",
            buttons:[
                {id:'config',text:'配置'},
                {id:'file',text:'文件'},
            ],
            configTab:'',
            fileTab:'',
            docsifyTemplate:`<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta charset="UTF-8" />
    <title></title>
    <!-- CSS file -->
    <!-- CSS_FILE -->
  </head>
  <body>
    <div id="app">请稍候...</div>
    <!-- js code -->
    <!-- CODE -->
    <!-- js file -->
    <!-- JS_FILE -->
  </body>
</html>            
`,
            main:{$main},
            themesActive:'vue',
            isShow:true,
            activePlugins:"",
            resizing:false,
            files:{
               '':{dir:[],file:[]}
            },
            modalTitle:"",
            modalActive:false,
            pluginsFrom:{
                name:"",
                usage:"",
                describe:'',
                assetsCss:"",
                assetsJs:"",
                home:'',
                md:''
            },
            pluginsSelectAll:false,
            AceEditor:{
                ext:"ace/ext/settings_menu",
                theme:"ace/theme/monokai",
                mode:"ace/mode/markdown",
                isShow: this.tab === 'editor' ? true : false
            },
            AceEditorFile:'',
            AceEditorFileText:''
        }
    },
    watch:{
        fileTab(value){
             this.fileList(value)
        },
        themesActive(value){
            this.main.themesEnable =value
        }
    },
    computed: {
        metaboxStyle() {
            return {
              display: this.active === "" ? "" : "block"
          }
        },
        isCheckbox(){
            return this. activePlugins in  this.pluginsEnable
        },
        css(){
            let array =[...((this.main.themes)[this.themesActive]).css];
            let plugins = this.main.pluginsEnable
            for (let i=0;i<= plugins.length-1;i++){
                let plugin = (this.main.plugins)[plugins[i]]
                if(plugin ){
                    array.push(...plugin.assets.css);
                }
            }
          
            return array
        },
        js(){
            let array =["@DocsifyAsset/js/docsify.js"];
            let plugins = this.main.pluginsEnable
            for (let i=0;i<= plugins.length-1;i++){
                let plugin = (this.main).plugins[plugins[i]]
                if(plugin ){
                    array.push(...plugin.assets.js);
                }
            }
            return array
        },
        activeDocsifyConfig(){
            let tmp = this.main
            tmp.docsify.basePath  =this.ajaxurl+'?action=docsify/markdown&file='
            let array ={...tmp.docsify};
            let plugins = tmp.pluginsEnable
            for (let i=0;i<= plugins.length-1;i++){
                let plugin = (tmp.plugins)[plugins[i]]
              
                if(plugin){
                    array = {...array,...plugin.usage}
                }
            }
            return array
        },
        configMain(){
            let tmp = this.main
            let array ={...tmp.docsify};
            return array
        },
        code(){
            let js ='\\n        window.\$docsify =';
            js += JSON.stringify(this.activeDocsifyConfig, null,4)+'\\n' 
            return js.replace(/(^|\\n)/g, '$1        ');
        },
        codeEditor(){
            let first_str ='//Setting: win: \"Ctrl-q\", mac: \"Ctrl-q\"\\n//Save: win: \"Ctrl-s\", mac: \"Ctrl-s\"\\nwindow.\$docsify ='
            const config ={
                '':{
                   mode:"ace/mode/javascript",
                   value:first_str+JSON.stringify(this.configMain, null,4),
                   style:{
                     minHeight: (JSON.stringify(this.configMain, null,4).split('\\n').length +5) *16 +"px"  
                   } 
                },
                template:{
                    mode:"ace/mode/html",
                    value:this.docsifyTemplate,
                    style:{
                     minHeight: this.docsifyTemplate.split('\\n').length *16 +"px"  
                   } 
                },
                html:{
                    mode:"ace/mode/html",
                    value:this.\$refs.view.html ,
                    style:{
                     minHeight: this.\$refs.view.html.split('\\n').length *16 +"px"  
                    } 
                }
            }
            return config[this.configTab]
        },
        pluginsFromObject(){
            let key = this.pluginsFrom.name || "";
            return    {
                [key] :{
                   usage:(() => { 
                       try { 
                           return JSON.parse(this.pluginsFrom.usage); 
                       } catch (error) {
                           return {};
                       }})() ,
                   describe: this.pluginsFrom.describe|| "",
                   assets: {
                       css:this.pluginsFrom.assetsCss.split('\\n').filter(element => element.trim() !== '') || [],
                       js:this.pluginsFrom.assetsJs.split('\\n').filter(element => element.trim() !== '') || []
                   },
                   home: this.pluginsFrom.home||'',
                   md: this.pluginsFrom.md||'',
                }
            }
        },
        pluginsFromDefault(){
            return {
                name:"",
                usage:"",
                describe:'',
                assetsCss:"",
                assetsJs:"",
                home:'',
                md:''
            }
        },
    },
    methods: {
        welcome(){
            let active =false
            let cookies = document.cookie.split('; ');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i].split('=');
                if (cookie[0] === 'docsify') {
                    active = true;
                }
            }
            if(active){
                this.notice("感谢使用本插件! 请前往<a href='/wp-admin/options-permalink.php'>固定链接</a>刷新路由。",'notice-success')
                document.cookie ='docsify=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }
        },
        /**
         * 根据按钮id和索引动态设置按钮的样式
         * @param {string} id - 按钮id
         * @param {number} index - 按钮索引
         * @returns {Object}
         */
        buttonStyle(id, index) {
            return {
                marginRight: index < this.buttons.length - 1 ? '5px' : '0',
                visibility: (this.active !== "" && this.active !== id) ? "hidden" : ""
            }
        },
        /**
         * 切换激活按钮
         * @param {string} index - 按钮id
         */
        buttonChange(index) {
            if (this.active === index) {
                this.active = '';
            } else {
                this.active = index;
            }
        },
        changeHeight() {
            $('#wpbody-content').css('padding-bottom', '0px')
            let  screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            if(screenWidth<=767){
                this.isShow=false;
            }
            let wrap = $('#wpwrap').height();
            let bar = $('#wpadminbar').height();
            let body = $("#wpbody-content").height();
            let editor = $('#content').height();
            if(wrap - bar - body >0){
                let h = (wrap - bar - body+ editor)
              
                $('#content').height(h);
            }
 
        },
        isPluginsReadme(title,activePlugins){
            const container = document.createElement('div');
            this.\$el.appendChild(container);
            let ApiWrapComponent = Vue.extend({
                template: '<api-wrap :title="title" :active="active" @close="close">' +
                 '<docsify-iframe   :html-template="docsifyTemplate"  :css="css" :js="js" :alias="alias" :code="code"></docsify-iframe>'+
                 '</api-wrap>',
                props: ['title','active'],
             
            });
            this.\$el.appendChild(container);
            let  apiWrapInstance = new ApiWrapComponent({
                propsData: {
                    title: this.StringHelper.capitalizeFirstLetter(title),
                    active:true,
                }
            });
            
            apiWrapInstance.css = [...((this.main.themes)[this.themesActive]).css]
            apiWrapInstance.js = ['@DocsifyAsset/js/docsify.js'];
            apiWrapInstance.alias = this.main.alias
            apiWrapInstance.code ='\\nwindow.\$docsify ='+ JSON.stringify({
                name: title,
                homepage: activePlugins.md
                },null,4);
            apiWrapInstance.docsifyTemplate = this.docsifyTemplate;
            apiWrapInstance.close =(e)=>{
                apiWrapInstance.\$destroy();
                apiWrapInstance.\$el.parentNode.removeChild(apiWrapInstance.\$el);
            }
            apiWrapInstance.\$mount(container);
        },
        clickCheckbox(){
            if(this. activePlugins in  this.pluginsEnable){
                const index = this.main.pluginsEnable.indexOf(this. activePlugins);
                if (index !== -1) {
                  this.main.pluginsEnable.splice(index, 1);
                }
            }else {
                this.main.pluginsEnable.push(this. activePlugins )
            }
        },
        fileList(path=''){
            this.jQuery.ajax({
                 type: 'GET',
                 url:this.ajaxurl+'?action=docsify/file&path='+path ,
                 success:(result)=>{
                     if(result.code ==1){
                         if(result.data.type=='dir'){
                             let tmp ={dir:[],file:[],assets:{}};
                             if(result.data.assets.dir.length>0){
                                result.data.assets.dir.forEach((item)=>{
                                   tmp.dir.push({name:item,type:'dir',path:path+'/'+item});
                                   (tmp.assets)[path+'/'+item] ={dir:[],file:[],assets:[]}
                               }); 
                             }
                             if(result.data.assets.details.length>0){
                                 result.data.assets.details.forEach((item)=>{
                                  tmp.file.push({
                                    type:'file',
                                    path:path+'/'+item.name,
                                    ...item
                                  })
                                });
                             }  
                               
                             (this.files)[path].dir = [...tmp.dir];
                             (this.files)[path].file =[...tmp.file];
                             (this.files) = {...this.files,...tmp.assets}
                         }
                     }
                 },
                 error:(result)=>{
                     console.log(result)
                 }
            })
        },
        fileApi(path){
          return new Promise((resolve, reject)=>{
              this.jQuery.ajax({
                   type: 'GET',
                   url:this.ajaxurl+'?action=docsify/file&path='+path ,
                   success:(result)=>{
                      resolve(result)
                   },
                   error:(result)=>{
                       reject(result)
                   }
              })
          })
        },
        fileClick(item){
            this.fileTab = item.path
        },
        fileUp(){
            let path = this.fileTab.replace(/\/[^\/]*$/, '');
            this.fileTab = path
        },
        // 发布markdown文件资源包
        publishMarkdown(){
             this.jQuery.ajax({
                 type: 'PATCH',
                 url:this.ajaxurl+'?action=docsify/file',
                 success:(res)=>{
                     if(res.code ==1){
                          let item = res.data
                          if(item.name && item.url){
                              this.\$set(this.main.docsify, 'basePath', item.url);
                              var protocol = window.location.protocol;
                              var domain = window.location.hostname;
                              var port = window.location.port;

                              var fullDomainWithPort = domain;
                              if (port !== '') {
                                    fullDomainWithPort += ':' + port;
                              }
                              this.main.alias = this.ArrayHelper.addOrReplaceItem(this.main.alias,"name","@MarkdownAsset",item);
                              this.notice(
                                  "<a href='"+protocol + "\/\/" + fullDomainWithPort+"/docsify' target='_blank'>预览<a>",
                                  'notice-success',this.fileNoticeCallback
                              )
                          }
                     }else {
                         this.notice(res.message,'notice-error',this.fileNoticeCallback)
                     }
                 },
                 error:(res)=>{
                     console.log(res)
                 }
             })
        },
        // help notice
        fileNoticeCallback(el){
           this.\$refs.fileNotice.append(el);
        },
        configNoticeCallback(el){
            this.\$refs.configNotice.append(el);
        },
        // 保存主配置文件设置
        saveConfig(){
            console.log(this.main)
            this.jQuery.ajax({
                 type: 'PUT',
                 url:this.ajaxurl+'?action=docsify/file',
                 data: JSON.stringify(this.main), // 将数据转换为 JSON 字符串
                 contentType: 'application/json',
                 success:(res)=>{
                     if(res.code ==1){
                          this.min = res.data
                          this.notice(res.message,'notice-success',this.configNoticeCallback)
                     }else {
                         this.notice(res.message,'notice-error',this.configNoticeCallback)
                     }
                 },
                 error:(res)=>{
                     
                     console.log(res)
                 }
             })
        },
        // 保存主配置
        saveMainConfig(ace){
            let config_json = ace.getValue();
            let first_str ='//Setting: win: \"Ctrl-q\", mac: \"Ctrl-q\"\\n//Save: win: \"Ctrl-s\", mac: \"Ctrl-s\"\\nwindow.\$docsify ='
            config_json = config_json.replace(first_str,"")
            let config = {}
            try {
                config = JSON.parse(config_json);
                this.main.docsify = config
                 this.notice("Success 发布后生效",'notice-success',this.configNoticeCallback)
            } catch (error) {
               this.notice("未找到匹配的内容。",'notice-error',this.configNoticeCallback)
               
            }
        },
        // 新增文件
        async createFile(){
            let file = await this.confirm('创建新文件或目录')
            file= file.startsWith('/') ? file : '/' + file;
            if(file && file.length>0){
                this.jQuery.ajax({
                 type: 'POST',
                 url:this.ajaxurl+'?action=docsify/file',
                 data:{
                     path:file,
                     text:""
                 },
                 success:(res)=>{
                     if(res.code ==1){
                          this.fileList('')
                     }else {
                         this.notice(res.message,'notice-error')
                     }
                 },
                 error:(res)=>{
                 }
             }) 
            }else{
                this.notice('文件或目录名称不能为空','notice-error')
            }
            
        },
        // 编辑文件
        async editorFile(file){
            this.tab='editor'
            this.active = '';
            let result = await this.fileApi(file)
            if(result.code && result.code ==1){
                if(result.data.type && result.data.type ==="file"){
                   this.AceEditorFileText ="Setting: win: \"Ctrl-q\", mac: \"Ctrl-q\"\\nSave: win: \"Ctrl-s\", mac: \"Ctrl-s\""
                    setTimeout(()=>{
                        this.AceEditorFile = file
                        this.AceEditorFileText = result.data.text
                    },1000)
                }
            }
        },
        // 删除文件
        deleteFile(file){
            file= file.startsWith('/') ? file : '/' + file;
            if(window.confirm("你确定要删除\""+file+"\"吗？")){
              this.jQuery.ajax({
                   type: 'DELETE',
                   url:this.ajaxurl+'?action=docsify/file&path='+file,
                   success:(res)=>{
                       if(res.code ==1){
                           this.notice(res.message,'notice-success',this.fileNoticeCallback)
                       }else {
                           this.notice(res.message,'notice-error',this.fileNoticeCallback)
                       }
                   },
                   error:(res)=>{
                   }
              })    
            }
            
        },
        // 保存文件
        saveFile(ace){
            this.AceEditorFileText = ace.getValue();
            this.jQuery.ajax({
                 type: 'POST',
                 url:this.ajaxurl+'?action=docsify/file',
                 data: {
                     path:this.AceEditorFile,
                     text:window.btoa(encodeURIComponent(this.AceEditorFileText))|| ''
                     
                 },
                 success:(res)=>{
                     if(res.code ==1){
                          this.notice(res.message,'notice-success')
                     }else {
                         this.notice(res.message,'notice-error')
                     }
                 },
                 error:(res)=>{
                     console.log(res)
                     this.notice(res.message,'notice-success')
                 }
             })
        },

        // 新增主题
        createThemes(){
            
        },
        // 编辑插件
        editorThemes(ThemesName){
            
        },
        // 删除插件
        deleteThemes(ThemesName){
            
        },
        // 提交插件
        saveThemes(){
            
        },
        
        // 新增插件
        createPlugins(){
            this.modalTitle=  "新增插件"
            this.modalActive= true
            this.pluginsFrom=this.pluginsFromDefault
        },
        // 编辑插件
        editorPlugins(PluginsName){
            this.modalTitle=  PluginsName,
            this.modalActive= true
            let tmp = this.main.plugins[PluginsName]
            this.pluginsFrom={
                name:PluginsName,
                usage:JSON.stringify(tmp.usage,null,4) || "{}",
                describe:tmp.describe || "",
                assetsCss:tmp.assets.css.join('\\n')|| "",
                assetsJs:tmp.assets.js.join('\\n')|| "",
                home:tmp.home || "",
                md:tmp.md || "",
            }
        },
        // 删除插件
        deletePlugins(PluginsName){
            if (this.main.plugins.hasOwnProperty(PluginsName)) {
                this.\$delete( this.main.plugins, PluginsName )
                this.notice("Success",'notice-success',this.configNoticeCallback)
            }
        },
        // 提交插件
        savePlugins(){
            let config =this.pluginsFromObject
            if(config.name !=""){
                this.main.plugins = {...this.main.plugins,...config}
                this.notice("Success",'notice-success',this.configNoticeCallback)
                this.pluginsFrom=this.pluginsFromDefault
                this.modalTitle=  ""
                this.modalActive= false
            }else{
                this.notice("插件名称不能为空",'notice-error',this.configNoticeCallback)
            }
        },
        // 全选
        pluginsSelectAllInput(){
            if(this.pluginsSelectAll){
                this.main.pluginsEnable = Object.keys(this.main.plugins) 
            }else {
                this.main.pluginsEnable =[]
            }
        },
        useComponent(config){
            const ApiComponent = Vue.extend({
                template: config.template || "",
                props: Object.keys(config.props ) || [],
            });
            const  apiInstance = new ApiComponent({
                propsData: {...config.props}
            });
            apiInstance.\$mount(config.el)
        },
        
    },
    mounted(){
        
         window.addEventListener('resize', this.changeHeight);
         this.changeHeight() 
         this.fileList()
         this.welcome()
    }
})
 
JS;

