<?php
/**
 * Location Tracking Form.
 * Contains \Drupal\location_dhl_api\Form\LocationTrackerForm.
 */
namespace Drupal\location_dhl_api\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
// use Drupal\Core\Url;
// use Drupal\Core\Routing;

/** 
 * Class to display location based tracking Form.
 * 
 */
class LocationTrackerForm extends FormBase {
  /**
   * Get unique Id for the form.
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'location_tracking_form';
  }
  
  /**     
   * Build and process form using unique Id.
   * @param array $form.
   * @param interface $form_state.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['countryCode'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter 2-letter country code:'),
      '#required' => TRUE,
    );
    $form['addressLocality'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter City:'),
      '#required' => TRUE,
    );
    $form['postalCode'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Postal Code:'),
      '#required' => TRUE,
    );
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    );
    return $form;
  }
  
  /**
   * Validate form values through controller.
   * @param array $form.
   * @param interface $form_state.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
		if (!$form_state->getValue('countryCode') || empty($form_state->getValue('countryCode'))) {
            $form_state->setErrorByName('countryCode', $this->t('Please enter Country Code'));
        }
    if (!$form_state->getValue('addressLocality') || empty($form_state->getValue('addressLocality'))) {
          $form_state->setErrorByName('addressLocality', $this->t('Please enter City'));
      }
    if (!$form_state->getValue('postalCode') || empty($form_state->getValue('postalCode'))) {
        $form_state->setErrorByName('postalCode', $this->t('Please enter Postal Code'));
    }
    if(strlen($form_state->getValue('countryCode')) > 2) {
      $form_state->setErrorByName('countryCode', $this->t('Please enter a valid Country Code'));
    }
  }

  /**
   * Process form submission after all validation checks.
   * @param array $form.
   * @param interface $form_state.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try{
      $conn = Database::getConnection();
      
      $field = $form_state->getValues();
       
      $fields["country_code"] = $field['countryCode'];
      $fields["city"] = $field['addressLocality'];
      $fields["zip"] = $field['postalCode'];
      
        $conn->insert('address')
           ->fields($fields)->execute();
        \Drupal::messenger()->addMessage($this->t('The address has been succesfully saved'));
       
    } catch(Exception $ex){
      \Drupal::logger('location_dhl_api')->error($ex->getMessage());
    }
  }

}