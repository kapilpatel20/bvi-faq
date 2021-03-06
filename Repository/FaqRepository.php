<?php

namespace BviFaqBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FaqRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FaqRepository extends EntityRepository
{
    public function getList($params = array()) {
        
        $query      = $this->createQueryBuilder('cm');
        $refineQtr  = $this->prepareFilters($query,$params);
        
        return $refineQtr;
        
    }
    
    //prepare filter operation
    
    public function prepareFilters($query,$params) {
        
        $sortOrd= 'desc'; $sortBy = 'cm.id';
        
        if(isset($params['question']) && $params['question']!='') {
            $query->where( $query->expr()->like('cm.question', ':TITLE'));
            $query->setParameter('TITLE','%'.$params['question'].'%');
        }
        if(isset($params['status']) && $params['status']!='') {
            $query->andWhere('cm.status=:STATUS');
            $query->setParameter('STATUS',$params['status']);
        }
        if(isset($params['sortBy']) && $params['sortBy']!='') {
            $sortBy = 'cm.'.$params['sortBy'];
        }
        if(isset($params['sortOrd']) && $params['sortOrd']!='') {
            $sortOrd = $params['sortOrd'];
        }
        
        $query->orderBy($sortBy, $sortOrd);
       # echo $query->getQuery()->getSql();die;
        return $query;
    }
}
