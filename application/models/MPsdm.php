<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MPsdm extends CI_Model
{

    function buildTree(array $elements, $parentId = 0)
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['org_parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['org_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
                unset($elements[$element['org_id']]);
            }
        }
        return $branch;
    }

    function flatten($element)
    {
        $flatArray = array();
        foreach ($element as $key => $node) {
            if (array_key_exists('children', $node)) {
                $flatArray = array_merge($flatArray, $this->flatten($node['children']));
                unset($node['children']);
                $flatArray[] = $node;
            } else {
                $flatArray[] = $node;
            }
        }
        return $flatArray;
    }

    public function get_struktur_organisasi()
    {
        $sql = $this->db->order_by('a.org_id')
            ->join('m_struktur_organisasi b', 'a.org_parent = b.org_id', 'left')
            ->select('a.*, b.org_nama as parent_name')
            ->where('a.org_record_status', 'A')
            ->get('m_struktur_organisasi a');
        $arrso = [];
        foreach ($sql->result_array() as $value) {
            $arrso[] = $value;
        }
        $tree = $this->buildTree($arrso);
        $arr = $this->flatten($tree);
        usort($arr, function ($a, $b) {
            return $a['org_id'] - $b['org_id'];
        });
        return $arr;
    }

    public function get_mapping_jabatan($uk_id)
    {
        $data = $this->db->join('m_jabatan', 'jm_jb_id = jb_id')
            ->get_where('m_jabatan_mapping', ['jm_uk_id' => $uk_id]);
        return $data;
    }

    public function search_mapping_jabatan($word)
    {
        $sql = $this->db
            ->join('m_jabatan', 'jb_id = jm_jb_id')
            ->join('m_unit_kerja', 'uk_id = jm_uk_id')
            ->order_by('jb_id, uk_nama')
            ->like('jb_nama', $word)
            ->get('m_jabatan_mapping');
        return $sql;
    }
}
