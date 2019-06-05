<?php

class ModelLocalisationContract extends Model {
    public function addContract($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "fl_contracts_status` SET `name` = '" . $data['name'] . "'");
    }

    public function editContract($contract_id, $data) {
        $this->db->query("UPDATE `" . DB_PREFIX . "fl_contracts_status` SET `name` = '" . $data['name'] . "' WHERE `contract_status_id` = '" . (int)$contract_id . "'");
    }

    public function deleteContract($contract_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "fl_contracts_status` WHERE `contract_status_id` = '" . (int)$contract_id . "'");
    }

    public function getTotalContracts($data) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "fl_contracts_status`";

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }					

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }	

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getContracts($data) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "fl_contracts_status`";

        $sort_data = array(
            'contract_status_id',
            'name'
        );	

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];	
        } else {
            $sql .= " ORDER BY contract_status_ids";	
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }					

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }	

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getContract($contract_id) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fl_contracts_status` WHERE `contract_status_id` = '" . (int)$contract_id . "'");

        return $query->row;
    }
}





?>