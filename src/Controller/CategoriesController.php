<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Hash;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 *
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['index']);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->Categories->recover();
        
        $categories = $this->Categories->find()
            ->contain(['Child1Categories' => ['Child2Categories']])
            ->where(['parent_id IS' => null, 'estado_id' => 1]);
        
        $this->set(compact('categories'));
        $this->set('_serialize', ['categories']);
    }
    
    /**
     * getAdmin method
     *
     * @return \Cake\Http\Response|void
     */
    public function getAdmin() {
        $this->Categories->recover();
        
        $categories = $this->Categories->find()
            ->contain(['Child1Categories' => ['Child2Categories']])
            ->where(['parent_id IS' => null, 'estado_id' => 1]);
        
        $this->set(compact('categories'));
        $this->set('_serialize', ['categories']);
    }
    
    
    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => ['Categories', 'Estados']
        ]);

        $this->set('category', $category);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $categories = $this->Categories->Categories->find('list', ['limit' => 200]);
        $estados = $this->Categories->Estados->find('list', ['limit' => 200]);
        $this->set(compact('category', 'categories', 'estados'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $categories = $this->Categories->Categories->find('list', ['limit' => 200]);
        $estados = $this->Categories->Estados->find('list', ['limit' => 200]);
        $this->set(compact('category', 'categories', 'estados'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
