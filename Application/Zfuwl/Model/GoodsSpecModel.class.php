<?php

namespace Zfuwl\Model;

use Zfuwl\Logic\GoodsSpecLogic;

class GoodsSpecModel extends CommonRelationModel {

    protected $_validate = array(
        array('name', 'require', '商品规格名必须填写！'), //默认情况下用正则进行验证
        array('type_id', 'checkTypeId', '商品模型必须选择', 0, 'callback'),
    );
    protected $_link = array(
        'goodstype' => array(
            'mapping_type' => self::HAS_ONE,
            'class_name' => 'goodsType',
            // 定义更多的关联属性
            'foreign_key' => 'id',
            'mapping_key' => 'type_id',
            'as_fields' => 'name:type_name'
        ),
        'spec_item' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'GoodsSpecItem',
            // 定义更多的关联属性
            'foreign_key' => 'spec_id',
        ),
    );

    /**
     * 判断type_id 是否合法 没有选择就返回false
     * @param $val
     * @return bool
     */
    protected function checkTypeId($val) {
        if (intval($val) < 1)
            return false;
        return true;
    }

    /**
     * 添加规则后，添加规则项
     * @param $data
     * @param $options
     * @return bool
     */
    protected function _after_insert($data, $options) {
        parent::_after_insert($data, $options);
        $spec_logic = new GoodsSpecLogic();
        if ($spec_logic->addSpecItem($data['id'])) {
            return true;
        }
        //  如果规则项添加失败，就删除当前规则
        $this->delete($data['id']);
        return false;
    }

    /**
     * 修改规则后，修改规则项
     * @param $data
     * @param $options
     * @return bool
     */
    protected function _after_update(&$data, $options) {
        parent::_before_update($data, $options); // TODO: Change the autogenerated stub
        $spec_logic = new GoodsSpecLogic();
        if ($res = $spec_logic->saveSpecItem($data['id'])) {
            return true;
        }
        return false;
    }

    /**
     * 删除商品规格时，先删除商品规格项值
     * @param $options
     * @return bool
     */
    protected function _before_delete($options) {
        parent::_before_delete($options); // TODO: Change the autogenerated stub
        $id = $options['where']['id'];
        $spec_logic = new GoodsSpecLogic();
        if ($res = $spec_logic->delSpecItem($id)) {
            return true;
        }
        return false;
    }

    /**
     * 获取规则 选项值
     * @param $id
     * @return string
     */
    public function getItems($id, $flag = ',') {
        $data = M('GoodsSpecItem')->where(['spec_id' => $id])->field('item')->select();
        $str = '';
        foreach ($data as $k => $v) {
            $str.=$v['item'] . $flag;
        }
        return rtrim($str, $flag);
    }

    /**
     * 获取帅选 的 规格
     */
    public function get_search_spec() {
        $where['is_index'] = 1;
        return $this->where($where)->getField("id,name");
    }

}
