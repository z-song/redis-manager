import Vue from 'vue';

import {
    Button, Select, Option, Input, Dropdown, DropdownMenu, DropdownItem, Menu,
    MenuItem, Submenu, ButtonGroup, Table, TableColumn, Tag, Row, Col, Card,
    Alert, Form, FormItem, InputNumber, Dialog, Header, Container, Aside, Main,
    Loading, MessageBox, Message
} from 'element-ui'

Vue.use(Button);
Vue.use(Select);
Vue.use(Option);
Vue.use(Input);
Vue.use(Dropdown);
Vue.use(DropdownMenu);
Vue.use(DropdownItem);
Vue.use(Menu);
Vue.use(MenuItem);
Vue.use(Submenu);
Vue.use(ButtonGroup);
Vue.use(Table);
Vue.use(TableColumn);
Vue.use(Tag);
Vue.use(Row);
Vue.use(Col);
Vue.use(Card);
Vue.use(Alert);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(InputNumber);
Vue.use(Dialog);
Vue.use(Header);
Vue.use(Container);
Vue.use(Aside);
Vue.use(Aside);
Vue.use(Main);

Vue.use(Loading.directive);

Vue.prototype.$loading = Loading.service;
Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$message = Message;