<!-- BEGIN: MAIN -->

<h2>{PHP.L.YourSubscriptions}</h2>

<!-- BEGIN: SUBSCRIPTIONS -->
<table>
  <thead>
    <tr>
      <th>{PHP.L.Description}</th>
      <th>{PHP.L.Type}</th>
      <th>{PHP.L.SubscribedSince}</th>
      <th>{PHP.L.LastNotification}</th>
      <th>{PHP.L.Action}</th>
    </tr>
  </thead>
  <tbody>
    <!-- BEGIN: ROW -->
    <tr>
      <td>{SUB_DESC}</td>
      <td>{SUB_AREA}</td>
      <td>{SUB_CREATED|cot_date('date_full',$this)}</td>
      <td><!-- IF {SUB_LASTSENT} -->{SUB_LASTSENT|cot_date('datetime_medium',$this)}<!-- ELSE -->{PHP.L.Never}<!-- ENDIF --></td>
      <td><a href="{SUB_ID|cot_url('notifier', 'a=unsubscribe&id=$this')}">{PHP.L.Unsubscribe}</a></td>
    </tr>
    <!-- END: ROW -->
  </tbody>
</table>
<!-- END: SUBSCRIPTIONS -->

<!-- BEGIN: NOSUBSCRIPTIONS -->
<p>{PHP.L.NoSubscriptions}</p>
<!-- END: NOSUBSCRIPTIONS -->

<h3>{PHP.L.SubscriptionSettings}</h3>

<form action="{SETTINGS_FORM_URL}" method="POST">
  <table>
    <thead>
      <tr>
        <th>{PHP.L.NotifyMe}</th>
        <th>{PHP.L.Frequency}</th>
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN: AREA -->
      <tr>
        <td>{SETTINGS_FORM_AREA_DESC}:</td>
        <td>{SETTINGS_FORM_AREA_FREQUENCY}</td>
      </tr>
      <!-- END: AREA -->
    </tbody>
  </table>
  <p>{SETTINGS_FORM_AUTOSUBSCRIBE}</p>
  <p>{SETTINGS_FORM_HTMLEMAIL}</p>
  <button type="submit">{PHP.L.Submit}</button>
</form>

<!-- END: MAIN -->