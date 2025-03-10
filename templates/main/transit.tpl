<div style="margin-top:20px;">
	<a href="{site_url('publish')}">Материал на свободную тему</a> (нажмите эту ссылку, и можете писать на любую тему)
</div>
<br>
<hr>
<div style="margin-top:10px;">
	<div><b>Материал на заданную тему</b> (ниже в выпадающем списке выберите интересующую вас тему и нажмите справа кнопку "Бронировать")</div>
	{if $num_topics > 0}
	<div style="margin-top:10px;">
	<form action="{site_url('booking')}" method="POST" id="booking">
	<table>
		<tr>
			<td>
				{$giventopics}
			</td>
			<td>
			
			<input type="button" name="submit" value="Бронировать"  onclick="modal_booking();return false;" style="font-size:10px;"/></td>
		</tr>
	</table>
		{form_csrf()}
		<!--input type="submit" name="submit" value="122"/-->
	</form>
		<!--button onclick="modal_booking();" >Бронировать</button-->

	</div>
	{else:}
		<div style="font-size:12px;">Материалов нет</div>
	{/if}
</div>
<div id="booking_message" style="display:none;">
&lt;&lt;Тема бронируется на 2-е суток! Если в течение этого времени вы не отправите готовую статью на модерацию, тема будет автоматически разбронирована, а недоделанная статья (если у нее будет статус "Черновик") будет удалена&gt;&gt;
</div>
<div id="container_call_booking" style="display:none;"><input type="button" name="continue" value="Продолжить" onclick="call_booking();"></div>

<form action="{site_url('booking')}" id="booking_form" method="POST" style="display:none">
<input type="hidden" name="giventopics" id="giventopics"/>
{form_csrf()}
</form>